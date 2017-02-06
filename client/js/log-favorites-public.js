var Favorites = (function( $ ){
  'use strict';

  var AJAXURL = log_favorites.ajaxurl;
  var _nonce;
  var _$element;

  function init( $btn ) {
    _$element = $btn;
    _$element.on( 'click', onClickHandler );
    generateNonce();
  }

  function onClickHandler( evt ) {
    evt.preventDefault();
    var postId = _$element.attr('data-postid');

    setState('loading');
    submitFavorite( postId, _nonce );
  }

  // Generate a nonce (workaround for cached pages/nonces)
  function generateNonce() {
    $.ajax({
      url: AJAXURL,
      type: 'post',
      datatype: 'json',
      data: {
        action : 'logfavorites_nonce'
      },
      success: function(data){
        console.log(data);
        _nonce = data.nonce;
      }
    });
  }

  function submitFavorite( postId, nonce ) {
    $.ajax({
      url: AJAXURL,
      type: 'post',
      datatype: 'json',
      data: {
        action : 'logfavorites_favorite',
        postid : postId,
        nonce: _nonce,
      },
      success: function(data) {
        console.log(data);
        if ( isActive() ) {
          setState('deactive');
        } else {
          setState('active');
        }
      }
    });
  }

  function setState( state ) {
    switch ( state ) {
      case 'loading':
        _$element.addClass('loading');
        break;

      case 'active':
        _$element.removeClass('loading');
        _$element.addClass('active');
        break;

      case 'deactive':
        _$element.removeClass('loading');
        _$element.removeClass('active');
        break;

      default:
        _state = state;
        break;
    }
  }

  function isActive() {
    return _$element.hasClass('active');
  }

  return {
    init: init,
  };
})( jQuery );

// init on jQuery ready
jQuery(function() {
  Favorites.init( jQuery('#favBtn') );
});
