/**
 * Alias for Wordpress' incredibly hacky process for using the media upload picker
 * @param  {string}   name - is assigned as the handle for this selector
 * @param  {string}   text - name of the opened window
 * @param  {Function} callback - callback to be executed on response
 * @return {null}
 */
function imageSelector( name, text, callback ){
    // call the default wordpress media handler with the additional name parameter
    tb_show(text, 'media-upload.php?media_handler=' + name + '&TB_iframe=true');

    var oldSend = window.send_to_custom_field;

    window.send_to_custom_field = function( response ){
        callback(response);
        window.send_to_custom_field = oldSend;
        tb_remove();
    }

    return void(0);
}

Evo = window.Evo || {};

var url = window.location.toString().split("/")[0];

Evo.ajaxRoute = url + '/wp-admin/admin-ajax.php';

/**
 * Generates a GUID/UUID
 * http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript
 * @return {string}
 */
Evo.guid = (function() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
               .toString(16)
               .substring(1);
  }
  return function() {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
           s4() + '-' + s4() + s4() + s4();
  };
})();

/**
 * Merges {html} with {values} by replacing vars available in
 * {values} that start with {delimiter}
 *
 * @param  {string} html
 * @param  {object} values
 * @param  {string} delimiter
 * @return {string}
 */
Evo.templateEngine = function( delimiter, endDelimiter ){
    var delimiter = delimiter,
        endDelimiter = endDelimiter || '',
        lastValues = {},
        lastGenerated = "";

    if( !delimiter ){
        delimiter = '{';
        endDelimiter = '}';
    }

    return {
        merge : function( html, values ){
            var htmlCopy = html;

            lastValues = values;

            for( var key in values ){
                var val = values[key],
                htmlCopy = htmlCopy.split( delimiter + key + endDelimiter ).join( val );
            }

            lastGenerated = htmlCopy;

            return this;
        },

        scrub : function(){
            var htmlCopy = lastGenerated,
                values = lastValues,
                regex = delimiter + '(\\w){0,}\\' + endDelimiter;

            htmlCopy = htmlCopy.replace( new RegExp( regex ), '' );

            lastGenerated = htmlCopy;

            return this;
        },

        generate : function(){
            return lastGenerated;
        }
    }
}

    function isFunction(functionToCheck) {
     var getType = {};
     return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
    }

    /**
     * Calling this function within a jquery $(window).resize() allows for update/reversion
     * of the dom based on breakpoint
     */
    function breakpoint( width, callbacks ){
        var setupExecuted = false;

        return function( ){
            var lessThanBreakpoint = window.innerWidth < width;

            if( lessThanBreakpoint && !setupExecuted ){
                setupExecuted = true;

                if ( typeof callbacks['setup'] === 'function' ) callbacks['setup']();

                return true;
            }
            else if( !lessThanBreakpoint && setupExecuted ){
                setupExecuted = false;

                if ( typeof callbacks['revert'] === 'function' ) callbacks['revert']();

                return true;
            }
        }

        return false;
    }

/**
 * Calling this function within a jquery $(window).scroll() allows for update/reversion
 * of the dom based on scrollpoint
 */
function scrollpoint( height, callbacks ){
        var setupExecuted = false;

        return function( ){
            var newHeight = height;

            if( isFunction(height) )
            {
                newHeight = function(){
                    return newHeight();
                }();
            }

            var lessThanBreakpoint = $(window).scrollTop() > newHeight;

            if( lessThanBreakpoint && !setupExecuted ){
                setupExecuted = true;

                if ( typeof callbacks['setup'] === 'function' ) callbacks['setup']();

                return true;
            }
            else if( !lessThanBreakpoint && setupExecuted ){
                setupExecuted = false;

                if ( typeof callbacks['revert'] === 'function' ) callbacks['revert']();

                return true;
            }
        }

        return false;
    }

