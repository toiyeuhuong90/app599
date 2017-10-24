jQuery(document).ready(function() {
  jQuery(window).resize(function() {
    var tops = jQuery('.qs-header-wrapper').height();

    if(jQuery(window).width() < 992) {
      jQuery('.wrapper-body-container').css('padding-top', 0);
      if(jQuery('.qs-header-wrapper').hasClass('fixed')) {
        jQuery('.qs-header-wrapper').removeClass('fixed');
      }
    } else {
      jQuery('.wrapper-body-container').removeAttr('style');
    }

    if(jQuery(window).width() > 767) {
      jQuery('.qs-img-advertisement').show();
      setTimeout(function() {
        jQuery('.qs-desc-advertisement').removeAttr('style').css('height', jQuery('.qs-advertisement').height() + 'px');
      }, 500);
    } else {
      setTimeout(function() {
        jQuery('.qs-desc-advertisement').removeAttr('style').css('background-image', 'url("' + jQuery('.qs-img-advertisement img').attr('src') +  '")');
      }, 500);
      jQuery('.qs-img-advertisement').hide();
    }

    if(jQuery('.qs-desc-col').length) {
      setTimeout(function() {
        jQuery('.qs-desc-col').css({'top' : (jQuery('.qs-img-discover').height() - 60) + 'px', 'height' : jQuery('.qs-img-discover').height() + 'px'});
      }, 500);
    }

    if(jQuery('.actions.qs-overlay-white').length) {
      setTimeout(function() {
        jQuery('.actions.qs-overlay-white').each(function() {
          jQuery(this).css('height', jQuery(this).parent().outerHeight() + 'px');
        });
      }, 500);
    }

    if(jQuery('.qs-row-statement').length) {
      if(jQuery(window).width() > 991) {
        jQuery('.qs-row-statement').each(function() {
          jQuery(this).find('.qs-content-statement-wrapper').css('height',  jQuery(this).find('.qs-col-statement-img').height() + 'px');
        });
      } else {
        jQuery(this).find('.qs-content-statement-wrapper').removeAttr('style');
      }
    }

  });

  jQuery('.qs-icon-search i').click(function() {
    if(jQuery('.qs-search-bar').is(':hidden')) {
      jQuery('.qs-search-bar').slideDown('fast');
      jQuery('.qs-header-wrapper').css('height', 'auto');
    } else {
      jQuery('.qs-search-bar').slideUp('fast');
      jQuery('.qs-header-wrapper').removeAttr('style');
    }
  });

  jQuery(document).mouseup(function (e) {
    if (!jQuery('.qs-search-form').is(e.target) && jQuery('.qs-search-form').has(e.target).length === 0) {
      jQuery('.qs-search-bar').slideUp('fast');
      jQuery('.qs-header-wrapper').removeAttr('style');
    }
  });

  if(jQuery('#datetimepicker').length) {
    jQuery('#datetimepicker').datetimepicker({
      icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
      },
      format: 'hh:mm MM/DD/YYYY',
      minDate: new Date()
    });
  }

  if(jQuery('.qs-row-statement').length) {
    if(jQuery(window).width() > 991) {
      jQuery('.qs-row-statement').each(function() {
        jQuery(this).find('.qs-content-statement-wrapper').css('height',  jQuery(this).find('.qs-col-statement-img').height() + 'px');
      });
    }
  }

  if(jQuery('.qs-banner-page').length) {
    var images = jQuery('.qs-banner-page').attr('data-image-src');
    jQuery('.qs-banner-page').parallax({imageSrc: images});
  }

  jQuery(document).on('click', '.qs-quantity-control-cart i', function() {
    var qty = parseInt(jQuery(this).parent().find('input').val());
    if(jQuery(this).hasClass('fa-minus')) {
      if(qty > 1) {
        qty = qty - 1;
        jQuery(this).parent().find('input').val(qty)
      }
    } else {
      qty = qty + 1;
      jQuery(this).parent().find('input').val(qty)
    }
  });

  jQuery(window).load(function () {
    if(jQuery('.qs-desc-col').length) {
      setTimeout(function() {
        jQuery('.qs-desc-col').css({'top' : (jQuery('.qs-img-discover').height() - 60) + 'px', 'height' : jQuery('.qs-img-discover').height() + 'px'});
      }, 500);
    }

    if(jQuery(window).width() > 767) {
      if (jQuery('.qs-advertisement')) {
        setTimeout(function() {
          jQuery('.qs-desc-advertisement').css('height', jQuery('.qs-advertisement').height() + 'px');
        }, 500);
      }
    } else {
      setTimeout(function() {
        jQuery('.qs-desc-advertisement').removeAttr('style').css('background-image', 'url("' + jQuery('.qs-img-advertisement img').attr('src') +  '")');
      }, 500);
      jQuery('.qs-img-advertisement').hide();
    }
  });

  if(jQuery('.actions.qs-overlay-white').length) {
    setTimeout(function() {
      jQuery('.actions.qs-overlay-white').each(function() {
        jQuery(this).css('height', jQuery(this).parent().outerHeight() + 'px');
      });
    }, 500);
  }

  if(jQuery('#postcode').length) {
    jQuery('#postcode').keypress(function (e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    });
  }

  if(jQuery('#billing\\:postcode').length) {
    jQuery('#billing\\:postcode').keypress(function (e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    });
  }

  if(jQuery('#shipping\\:postcode').length) {
    jQuery('#shipping\\:postcode').keypress(function (e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    });
  }

  jQuery('.mobile-navigation').html(jQuery('.megamenu-mobile').html());

  var jPM = jQuery.jPanelMenu({
    menu: '.wrapper-mobile-menu',
    trigger: '.mobile-icon-menu'
  });
  jPM.on();
  jPM.setPosition('80%');

  // Table responsvie
  var table = jQuery('.responsive-table .table');
  jQuery('.responsive-table .table').after('<div class="table-mobile hidden-on-tablet"></div>');
  
      var markupTable = table.clone(),
          captionTable = table.find('caption').clone(),
          $tableResponsive = table.next('.table-mobile');
      // Add table into table-responsive
      $tableResponsive.append(captionTable);
      $tableResponsive.append(markupTable);
      // Change table into table-responsive to ul li
      var $tableResponsiveTable = jQuery('table', $tableResponsive),
          ul = jQuery('<ul class="table-list">');
      jQuery('tbody tr', $tableResponsiveTable).each(function () {
        var li = jQuery("<li>");
        var div = jQuery("<div>");
        jQuery('td', this).each(function () {
          var div = jQuery("<div>");
          var index = jQuery(this).index(),
              td = jQuery(this).html();
              if (jQuery('th').length) {
                var th = jQuery('thead tr th:eq('+ index +')', $tableResponsiveTable).html();
                if (th.length || th !== ' ') {
                  div.append(jQuery("<strong>").html(th));
                }
              }
          if (td.length) {
            div.append(jQuery("<p>").html(td));
          }

          li.append(div);
        });
        ul.append(li);
      });
      $tableResponsiveTable.replaceWith(ul);


    jQuery('.form-list label').click(function() {
      jQuery(this).toggleClass('is-active');
    });

    // Filter
    // jQuery('.list-icon-style li').each(function() {
    //   if (jQuery(this).hasClass('current')) {
    //     var $textActive = jQuery(this).text();
    //     console.log($textActive);
    //     jQuery('.js-show-list span').text($textActive);
    //   }
    // });

    jQuery('.js-show-list').click(function(){
      jQuery(this).parent().toggleClass('is-active');
    });
});



function calHeight(child, pah) {
  var maxHeight = 0;

  jQuery(child).each(function() {
    jQuery(this).removeAttr('style');
    var height = jQuery(this).height();
    maxHeight = (height > maxHeight) ? height: maxHeight;
  });

  jQuery(child).removeAttr('style').css('height', (maxHeight + pah) + 'px');
}

(function() {
  var isBootstrapEvent = false;
  if (window.jQuery) {
    var all = jQuery('*');
    jQuery.each(['hide.bs.dropdown',
      'hide.bs.collapse',
      'hide.bs.modal',
      'hide.bs.tooltip',
      'hide.bs.popover',
      'hide.bs.tab'], function(index, eventName) {
      all.on(eventName, function( event ) {
        isBootstrapEvent = true;
      });
    });
  }
  var originalHide = Element.hide;
  Element.addMethods({
    hide: function(element) {
      if(isBootstrapEvent) {
        isBootstrapEvent = false;
        return element;
      }
      return originalHide(element);
    }
  });
})();
