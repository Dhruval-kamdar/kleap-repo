/**
 * Handles pulling from VAT quotes.
 * @param {object} event 
 */
var wuPullVATData = function wuPullVATData(event) {

  var that = this;
  this.loading = true;
  event.preventDefault();

  // Start Axios Call 
  jQuery
    .getJSON(
      ajaxurl +
      "?action=wu_get_eu_vat_tax_rates&rate_type=" +
      this.rate_type
    )
    .done(function (response) {
      //console.log(response);
      that.loading = false;

      // Remove VAT
      var no_vat = jQuery(that.data).filter(function (index, item) {
        if (item.type === "eu-vat") {
          that.delete.push(item);
        }
        return item.type !== "eu-vat";
      });

      that.data = no_vat.get().concat(response);
    })
    .fail(function (error) {
      that.loading = false;
      that.error = true;
      that.errorMessage = error.statusText;
    });

}; // end pullVATData;

// Listen for the event.
window.addEventListener('vue_loaded', function (e, vue) {

  if (typeof e.vue !== 'undefined') {

    // console.log(e.vue);
    pullVATData = wuPullVATData.bind(e.vue);

  } // end if;

  // e.target matches window
}, false);