jQuery( window ).on( 'elementor:init', function() {


    elementor.hooks.addAction( 'panel/open_editor/widget/ee-offcanvas', function( panel, model, view ) {

		if ( 'ee-offcanvas.classic' !== view.$el.data('element_type') )
			return;

		elementor.channels.editor.off('ee:editor:offcanvas:open').on('ee:editor:offcanvas:open', function( event ) {

			var $element 		= view.$el,
				$element_id 	= $element.data('id'),
				$slidebar 		= window.elementor.$previewContents.find( '#ee-offcanvas-' + $element_id ),
				ocController 	= window.elementorFrontend.eeOffcanvas.controller;

			ocController.toggle( 'oc' + $element_id );
		});
	} );

    var ControlSnazzy = elementor.modules.controls.BaseData.extend( {

		getSnazzyDefaultOptions: function getSnazzyDefaultOptions() {
			var options = this.model.get( 'snazzy_options' );
            
			return {
				containerCssClass 	: 'ee-select2-container',
				dropdownCssClass 	: 'ee-select2-dropdown',
				allowClear			: true,
				placeholder 		: this.model.get( 'placeholder' ),
				dir 				: elementor.config.is_rtl ? 'rtl' : 'ltr',
				ajax 				: {
					url 			: 'https://snazzymaps.com/' + options.endpoint + '.json',
					dataType		: 'json',
					cache			: true,
					data 			: function ( params ) {
						return {
							key 			: options.key,
							page 			: params.page || 1,
						};
					},
					processResults: function ( data, params ) {

						var styles = $.map( data.styles, function ( obj ) {

								obj.id 		= JSON.stringify( obj );
								obj.text 	= obj.name;

								return obj;
							});
						
						params.page = params.page || 1;

						var response = {
							results 	: styles,
							pagination 	: {
								more: ( params.page * 12 ) < data.pagination.totalItems,
							},
						};

						return response;
					},
				},
				minimumInputLength 		: 0,
				minimumResultsForSearch : -1,
				escapeMarkup 			: function ( markup ) { return markup; },
				templateResult 			: this.formatResults,
  				templateSelection 		: this.formatResultsSelection,
			};
		},

		formatResults: function formatRepo( repo ) {
			if ( repo.loading ) {
				return repo.text;
			}

			var markup =
					"<div class='ee-select2-dropdown__option clearfix'>" +
					"<div class='ee-select2-dropdown__option__image'><img src='" + repo.imageUrl + "'/></div>" +
					"</div>";

			return markup;
		},

		formatResultsSelection: function formatRepoSelection( repo ) {
			return repo.text;
		},

		applySavedValue: function applySavedValue() {
			var controlValue = this.getControlValue();

			if ( controlValue && '' !== controlValue ) {
				controlValue = JSON.parse( this.getControlValue() );

				this.setInputValue('[data-setting="' + this.model.get('name') + '"]', controlValue.id );
			}
		},

		getSnazzyOptions: function getSnazzyOptions() {
			return jQuery.extend(this.getSnazzyDefaultOptions(), this.model.get('select2options'));
		},

		onReady: function onReady() {
			this.ui.select.select2(this.getSnazzyOptions());
		},

		onBeforeDestroy: function onBeforeDestroy() {
			if (this.ui.select.data('select2')) {
				this.ui.select.select2('destroy');
			}

			this.$el.remove();
		}
    } );
    
    elementor.addControlView( 'ee-mb-snazzy', ControlSnazzy );
} );