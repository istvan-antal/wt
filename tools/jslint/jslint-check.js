load(arguments.shift() + "/jslint.js");

var opts = {
        indent: 4,
        passfail: true, 
        white: false, 
        browser: true, 
        onevar: false, 
        undef: false, 
        nomen: true, 
        eqeqeq: true, 
        plusplus: true, 
        bitwise: false, 
        regexp: true, 
        newcap: false, 
        immed: false, 
        strict: true
    }, 
    files = [];


arguments.forEach(function (arg, i) {
    var t;
    if (arg.indexOf("=") > -1) {
        t = arg.split('=');
        if (t[1] === 'true') {
            t[1] = true;
        }
        if (t[1] === 'false') {
            t[1] = false;
        }
        opts[t[0]] = t[1];
        
        if (t[0] === 'predef') {
            opts[t[0]] = opts[t[0]].split(',');
        }
        
    } else {
        files.push(arg);
    }
});

do {
    var curr = files.shift();
    var src = readFile(curr);
    //evil: true, forin: true, maxerr: 100

    
    
    

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

} while (files.length);

print( "JSLint check passed." );
java.lang.System.exit(0);

