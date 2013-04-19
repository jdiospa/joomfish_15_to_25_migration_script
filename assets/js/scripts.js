/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved 
 * @file scripts.js
 * @author ps
 */
$(document).ready( function() {		
	//get url for image etc
	var $url = $.getBaseUrl();
	
	//clear config form
	$('#db-data input:not(#submit-conn-data').bind( 'focus', function(e){
		 $(this).val('');
	});

	$('#clear-terminal').click( function() {/*reset console click event*/
		console.log('clicked!');
		$('#terminal-inner').html('clear...<br />');
	});
	$('#submit-conn-data').bind( 'click', function(e) {/*ini click event*/
		e.preventDefault();
		$.writeToIni('writing to ini, please wait...');
	});
	$('#table-migrate').click( function(e) {/*tables click event*/
		e.preventDefault();
		$.write_jf_to_db( $url+'ajax/migrate_joomfish_tables.php', 'migrating jf tables, please wait...' );
	});
	$('#content-migration').click( function(e) {/*content click event*/
		e.preventDefault();
		console.log('clicked content migration!');
		$.write_jf_to_db( $url+'ajax/write_jf_content_to_content.php', 'migrating content, please wait...' );
	});
	$('#migrate-modules').click( function(e) {/*modules click event*/
		e.preventDefault();
		console.log('clicked modules migration!');
		$.write_jf_to_db( $url+'ajax/write_jf_modules_to_modules.php', 'migrating modules, please wait...' );
	});
	$('#migrate-menus').click( function(e) {/*menus click event*/
		e.preventDefault();
		console.log('clicked menus migration!');
		$.write_jf_to_db( $url+'ajax/write_jf_menus_to_menus.php', 'migrating jf content menus, please wait...' );
	});
	$('#migrate-categories').click( function(e) {/*categories click event*/
		e.preventDefault();
		console.log('clicked categories migration!');
		$.write_jf_to_db( $url+'ajax/write_jf_categories_to_categories.php', 'migrating cateogory entries, please wait...' );
	});
	
});  
// Ultra-basic jQuery plugin pattern.
(function($){
      
      var myPrivateProperty = 1;
      var $url = null;
      
      $.getBaseUrl = function(){
        // get the url for ajaxloader and serverside file
    	  $url = location.href;
    	  // (without index.php)
    	  $url = $url.substr(0, $url.length-9);
    	  return $url;
      };
      $.clearInputValues = function(){
    	  $(this).val('');
          // Your non-element-specific jQuery method code here.
       };
       $.writeToIni = function( loader_text ){
    	   var $data = $('#db-data').serialize();
    	   $.ajax({
    		   url: "ajax/write_conn_data.php",
    		   data: $data,
    		   type: 'post',
    		   beforeSend: function() {
    			    $('#terminal').append("<span id='response'><img  src='"+$url+"/assets/img/ajax-loader.gif' /> <p id='loader-txt'>"+loader_text+"</p></span>");
    			  },
    			  success: function(response) {
    				  console.log('removed');
    				  setTimeout( function() {
    					  $('#response').remove();
    					}, 2000);
    				  $('#terminal-inner').append( response );
    			  }
    		   });
        };
        $.write_jf_to_db = function( url_db, loader_text ){
        	/**
        	 * call async classes to create and crud db and tables
        	 * @param url_db the async server files located in the ajax folder
        	 */
     	   $.ajax({
     		   url: url_db,
     		   type: 'post',
     		   beforeSend: function() {
     			    $('#terminal').append("<span id='response'><img  src='"+$url+"/assets/img/ajax-loader.gif' /> <p id='loader-txt'>"+loader_text+"</p></span>");
     			  },
     			  success: function(response) {
     				  console.log('removed');
     				  setTimeout( function() {
     					  $('#response').remove();
     					}, 2000);
     				  $('#terminal-inner').append( response );
     			  }
     		   });
         };
      // Call this public method like $(elem).myMethod();
      $.fn.myMethod = function(){
        return this.each(function(){
          // Your chainable "jQuery object" method code here.
        });
      };
      
      function myPrivateMethod(){
        // More code.
      };
      
})(jQuery); 
    
