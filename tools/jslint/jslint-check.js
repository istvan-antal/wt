load(arguments.shift() + "/jslint.js");

do {
    var src = readFile(arguments.shift());
    //evil: true, forin: true, maxerr: 100

    var opts = {
        passfail: true, 
        white: true, 
        browser: true, 
        onevar: true, 
        undef: true, 
        nomen: true, 
        eqeqeq: true, 
        plusplus: true, 
        bitwise: true, 
        regexp: true, 
        newcap: true, 
        immed: true, 
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
                print( "\n" + w.evidence + "\n" );
                print( "    Problem at line " + w.line + " character " + w.character + ": " + w.reason );
            }
        }
    }

    if ( found > 0 ) {
        print( "\n" + found + " Error(s) found." );
        java.lang.System.exit(1);
    }

} while (arguments.length);

print( "JSLint check passed." );
java.lang.System.exit(0);

