load(arguments.shift() + "/jslint.js");

do {
    var curr = arguments.shift();
    var src = readFile(curr);
    //evil: true, forin: true, maxerr: 100

    var opts = {
        indent: 4,
        passfail: true, 
        white: false, 
        browser: true, 
        onevar: false, 
        undef: false, 
        nomen: false, 
        eqeqeq: true, 
        plusplus: true, 
        bitwise: false, 
        regexp: true, 
        newcap: false, 
        immed: false, 
        strict: true
    }

    JSLINT(src, opts);  
    // All of the following are known issues that we think are 'ok'
    // (in contradiction with JSLint) more information here:
    // http://docs.jquery.com/JQuery_Core_Style_Guidelines
    var ok = {
        //"Expected an identifier and instead saw 'undefined' (a reserved word).": true,
        //"Use '===' to compare with 'null'.": true,
        //"Use '!==' to compare with 'null'.": true,
        //"Expected an assignment or function call and instead saw an expression.": true,
        //"Expected a 'break' statement before 'case'.": true,
        //"'e' is already defined.": true,
        //'Missing "use strict" statement': true
    };

    var e = JSLINT.errors, found = 0, w;

    //print("Errrors\n\n");


    for ( var i = 0; i < e.length; i++ ) {
    
        w = e[i];
        if (w) {
            if ( !ok[ w.reason ] ) {
                found++;
                if (w.evidence) {
                  print("\n" + w.evidence + "\n\n" );
                }
                print( curr + ":" + w.line + ":" + w.character + ": " + w.reason + '\n' );
            }
        }
    }

    if ( found > 0 ) {
        //print( "\n" + found + " Error(s) found." );
        java.lang.System.exit(1);
    }

} while (arguments.length);

print( "JSLint check passed." );
java.lang.System.exit(0);

