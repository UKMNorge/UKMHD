/**
 * This jQuery plugin resizes a textarea to adapt it automatically to the content.
 * @author Amaury Carrade
 * @version 1.1
 * 
 * @example jQuery('textarea').autoResize({
 *              animate:   {                            // @see .animate()
 *              	enabled:    true,                   // Default: false
 * 					duration:   'fast',                 // Default: 100
 *					complete:   function() {},          // Default: null
 *					step:       function(now, fx) {}    // Default: null
 *              },
 *              maxHeight: '500px'                      // Default: null (unlimited)
 *          });
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lessier General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lessier General Public License for more details.
 *
 * You should have received a copy of the GNU Lessier General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

(function(jQuery) {
	jQuery(document).ready(function() {
		jQuery('body').append('<div id="autoResizeTextareaCopy" style="box-sizing: border-box; -moz-box-sizing: border-box;  -ms-box-sizing: border-box; -webkit-box-sizing: border-box; visibility: hidden;"></div>');
		var jQuerycopy = jQuery('#autoResizeTextareaCopy');
		
		function autoSize(jQuerytextarea, options) { 
			// The copy must have the same padding, the same dimentions and the same police than the original.
			jQuerycopy.css({
				fontFamily:     jQuerytextarea.css('fontFamily'),
				fontSize:       jQuerytextarea.css('fontSize'),
				padding:        jQuerytextarea.css('padding'),
				paddingLeft:    jQuerytextarea.css('paddingLeft'),
				paddingRight:   jQuerytextarea.css('paddingRight'),
				paddingTop:     jQuerytextarea.css('paddingTop'), 
				paddingBottom:  jQuerytextarea.css('paddingBottom'), 
				width:          jQuerytextarea.css('width')
			});
			jQuerytextarea.css('overflow', 'hidden');
			
			// Copy textarea contents; browser will calculate correct height of copy.
			var text = jQuerytextarea.val();
			if(text == undefined || text == null || text == '') {
				return false;
			}
			text = text.replace(/\n/g, '<br/>');
			jQuerycopy.html(text + '<br />');
			
			// Then, we get the height of the copy and we apply it to the textarea.
			var newHeight = jQuerycopy.css('height');
			if(parseInt(newHeight.replace('px','')) < parseInt(options.minHeight.replace('px',''))) {
				newHeight = options.minHeight;
			}
			jQuerycopy.html(''); // We do this because otherwise, a large void appears in the page if the textarea has a high height.
			if(parseInt(newHeight) != 0) {
				if((options.maxHeight != null && parseInt(newHeight) < parseInt(options.maxHeight)) || options.maxHeight == null) {
					if(options.animate.enabled) {
						jQuerytextarea.animate({ 
							height: newHeight 
						}, {
							duration: options.animate.duration,
							complete: options.animate.complete,
							step:     options.animate.step,
							queue:    false
						});
					}
					else {
						jQuerytextarea.css('height', newHeight);
					}
					
					jQuerytextarea.css('overflow-y', 'hidden');
				}
				else {
					jQuerytextarea.css('overflow-y', 'scroll');
				}
			}
		}
		
		jQuery.fn.autoResize = function(options) { 
			var jQuerythis = jQuery(this),
			    defaultOptions = {
					animate: {
						enabled:   false,
						duration:  100,
						complete:  null,
						step:      null
					},
					maxHeight:     null,
					minHeight:     null
				};
			
			options = (options == undefined) ? {} : options;
			options = jQuery.extend(true, defaultOptions, options);

			jQuerythis.change ( function() { autoSize(jQuerythis, options); } ) 
				 .keydown( function() { autoSize(jQuerythis, options); } ) 
				 .keyup  ( function() { autoSize(jQuerythis, options); } )
				 .focus  ( function() { autoSize(jQuerythis, options); } );

			// No animations on startup
			startupOptions = options;
			startupOptions.animate.enabled = false;
			autoSize(jQuerythis, startupOptions);
		};
	});
})(jQuery);
