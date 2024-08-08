( function( $, _ ) {
	'use strict';

	var tribeWidget = {},
		$body = $( document.body );

	/**
	 * Better Search ID for Select2, compatible with WordPress ID from WP_Query
	 *
	 * @param  {object|string} e Searched object or the actual ID
	 * @return {string}   ID of the object
	 */
	tribeWidget.search_id = function ( e ) {
		var id = undefined;

		if ( 'undefined' !== typeof e.id ) {
			id = e.id;
		} else if ( 'undefined' !== typeof e.ID ) {
			id = e.ID;
		} else if ( 'undefined' !== typeof e.value ) {
			id = e.value;
		}
		return undefined === e ? undefined : id;
	};

	tribeWidget.calendar_toggle = function ( $wrapper ) {
		$wrapper.find( '.calendar-widget-filters-title' ).hide();
		$wrapper.find( '.calendar-widget-filters-operand' ).hide();
		var $hidden = $wrapper.find( '.calendar-widget-added-filters' );

		if ( $hidden.length ) {
			var calendar_filters = $hidden.val() ? JSON.parse( $hidden.val() ) : new Object();

			var count = 0;
			for ( var tax in calendar_filters ) {
				count += calendar_filters[tax].length;
			}

			if ( count > 0 ) {
				$wrapper.find( '.calendar-widget-filters-title' ).show();
				if ( count > 1 ) {
					$wrapper.find( '.calendar-widget-filters-operand' ).show();
				}
			}
		}
	};

	tribeWidget.select2 = function () {

		var $select = $( this ),
			args = {};

		if ( $select.hasClass( 'select2-container' ) ) {
			$select.select2( 'destroy' );
		}

		// If more than one version of select2 gets loaded, we can get duplicated dropdowns - and none of them work!
		if ( $select.next().hasClass( 'select2-container' ) ) {
			$select.next().remove();
		}

		if ( $( 'body' ).hasClass( 'wp-customizer' ) ) {
			args.dropdownCssClass = 'tribe-customizer-select2';
		}

		if ( !$select.is( 'select' ) ) {
			// Better Method for finding the ID
			args.id = tribeWidget.search_id;
		}

		// By default we allow The field to be cleared
		args.allowClear = true;
		if ( $select.data( 'prevent-clear' ) ) {
			args.allowClear = false;
		}

		// If we are dealing with a Input Hidden we need to set the Data for it to work
		if ( $select.data( 'options' ) ) {
			args.data = $select.data( 'options' );
		}

		// Prevents the Search box from showing
		if ( $select.data( 'hide-search' ) ) {
			args.minimumResultsForSearch = Infinity;
		}

		// Multiselect
		if ( $select.is( '[multiple]' ) ) {
			args.multiple = true;

			if ( !_.isArray( $select.data( 'separator' ) ) ) {
				args.tokenSeparators = [$select.data( 'separator' )];
			} else {
				args.tokenSeparators = $select.data( 'separator' );
			}
			args.separator = $select.data( 'separator' );

			// Define the regular Exp based on
			args.regexSeparatorElements = ['^('];
			args.regexSplitElements = ['(?:'];
			$.each( args.tokenSeparators, function ( i, token ) {
				args.regexSeparatorElements.push( '[^' + token + ']+' );
				args.regexSplitElements.push( '[' + token + ']' );
			} );
			args.regexSeparatorElements.push( ')$' );
			args.regexSplitElements.push( ')' );

			args.regexSeparatorString = args.regexSeparatorElements.join( '' );
			args.regexSplitString = args.regexSplitElements.join( '' );

			args.regexToken = new RegExp( args.regexSeparatorString, 'ig' );
			args.regexSplit = new RegExp( args.regexSplitString, 'ig' );
		}

		/**
		 * Better way of matching results
		 *
		 * @param  {string} term Which term we are searching for
		 * @param  {string} text Search here
		 * @return {boolean}
		 */
		args.matcher = function ( term, text ) {
			var result = text.toUpperCase().indexOf( term.toUpperCase() ) == 0; // eslint-disable-line eqeqeq,max-len

			if ( !result && 'undefined' !== typeof args.tags ) {
				var possible = _.where( args.tags, { text: text } );
				if ( args.tags.length > 0 && _.isObject( possible ) ) {
					var test_value = obj.search_id( possible[0] ); // eslint-disable-line no-undef
					result = test_value.toUpperCase().indexOf( term.toUpperCase() ) == 0; // eslint-disable-line eqeqeq,max-len
				}
			}

			return result;
		};

		// Select also allows Tags, so we go with that too
		if ( $select.data( 'tags' ) ) {
			args.tags = $select.data( 'options' );

			args.initSelection = function ( element, callback ) {
				var data = [];
				$( element.val().split( args.regexSplit ) ).each( function () {
					var obj = { id: this, text: this };
					if ( args.tags.length > 0 && _.isObject( args.tags[0] ) ) {
						var _obj = _.where( args.tags, { value: this } );
						if ( _obj.length > 0 ) {
							obj = _obj[0];
							obj = {
								id: obj.value,
								text: obj.text,
							};
						}
					}

					data.push( obj );
				} );
				callback( data );
			};

			args.createSearchChoice = function ( term, data ) { // eslint-disable-line no-unused-vars
				if ( term.match( args.regexToken ) ) {
					return { id: term, text: term };
				}
			};

			if ( 0 === args.tags.length ) {
				args.formatNoMatches = function () {
					return $select.attr( 'placeholder' );
				};
			}
		}

		// When we have a source, we do an AJAX call
		if ( $select.data( 'source' ) ) {
			var source = $select.data( 'source' );

			// For AJAX we reset the data
			args.data = { results: [] };

			// Allows HTML from Select2 AJAX calls.
			args.escapeMarkup = function( m ) {
				return m;
			};

			// instead of writing the function to execute the request we use Select2's convenient helper.
			args.ajax = {
				dataType: 'json',
				type: 'POST',
				url: window.ajaxurl,
				processResults: function( data ) {
					// parse the results into the format expected by Select2.
					$select.data( 'lastOptions', data.data );
					return data.data;
				},
			};

			// By default only send the source
			args.ajax.data = function ( search, page ) {
				return {
					action: 'tribe_widget_dropdown_' + source,
					disabled: $select.data( 'disabled' ),
					selected: $select.data( 'selected' ),
					search: search,
					page: page,
				};
			};
		}

		$select.on( 'open', function () {
			$( '.select2-drop' ).css( 'z-index', 10000000 );
		} ).trigger( 'change.select2' ).select2TEC( args );
	};

	tribeWidget.conditional = function( conditional, $widget ) {

		var $this = $( conditional ),
			field = $this.data( 'tribeConditionalField' ),
			$conditionals = $widget
				.find( '.js-tribe-conditional' )
				.filter( '[data-tribe-conditional-field="' + field + '"]' ),
			value = $this.val();

		// First hide all conditionals
		$conditionals.hide()

		// Now Apply any stuff that must be "conditional" on hide
		.each( function () {
			var $conditional = $( this );

			if ( $conditional.hasClass( 'tribe-select2' ) ) {
				$conditional.prev( '.select2-container' ).hide();
			}
		} )

			// Find the matching values
		.filter( '[data-tribe-conditional-value="' + value + '"]' ).show()

		// Apply showing with "conditions"
		.each( function () {
			var $conditional = $( this );

			if ( $conditional.hasClass( 'tribe-select2' ) ) {
				$conditional.hide().prev( '.select2-container' ).show();
			}
		} );
	};

	tribeWidget.showFilters = function( $widget ) {
		var $filter = $widget.find( '.calendar-widget-filters-container' );
		var $filterInput = $filter.find( '.calendar-widget-added-filters' );

		if (
			$filterInput.length &&
			$filterInput.val() &&
			$filterInput.val().length &&
			null !== $filterInput.val() &&
			'null' !== $filterInput.val()
		) {
			$filter.addClass( 'calendar-widget-filters-container--show' );

			if ( $('.fl-nanoscroller').length ) {
				// Let's be sane and ensure the object exists before trying to use it.
				if ( typeof FLBuilder !== undefined ) {
					FLBuilder._initScrollbars(); // eslint-disable-line no-undef
				}
			}
		}
	};

	tribeWidget.hideFilters = function( $widget ) {
		var $filter = $widget.find( '.calendar-widget-filters-container' );
		var $filterInput = $filter.find( '.calendar-widget-added-filters' );

		// If the input is missing, hide and bail.
		if ( ! $filterInput.length ) {
			$filter.removeClass( 'calendar-widget-filters-container--show' );
			return;
		}

		var val = $filterInput.val();

		// If we don't have a value or it's empty, hide and bail.
		if ( ! val || ! val.length ) {
			$filter.removeClass( 'calendar-widget-filters-container--show' );
			return;
		}

		val = JSON.parse( val );

		// Iterate through the taxonomies, if any have values, bail and don't hide.
		var remove = Object.keys( val ).reduce( function( acc, curr ) {
			if ( val[ curr ].length || null === val[ curr ].length ) {
				return false;
			}
			return acc;
		}, true );

		if ( ! remove ) {
			return;
		}

		$filter.removeClass( 'calendar-widget-filters-container--show' );
	};

	tribeWidget.setup = function( e, $widget ) {
		// If it's not set we try to figure it out from the Event
		if ( 'undefined' === typeof $widget ) {
			var $target = $( e.target );

			// Prevent weird non available widgets from going any further
			if (
				! $target.parents( '.widget-top' ).length ||
				$target.parents( '#available-widgets' ).length
			) {
				return;
			}

			$widget = $target.closest( 'div.widget' );
			$widget.find( '[data-depends]' ).trigger( 'setup.dependency' ).trigger( 'verify.dependency' );
		}

		if ( 'undefined' === typeof $widget ) {
			return;
		}

		// It might be a DOM object, so we try convert to jQuery
		if ( 'object' === typeof $widget ) {
			$widget = $( $widget );
		}

		// If by this point it's not an jQuery Object we bail
		if ( 'jQuery' === typeof $widget ) { // eslint-disable-line valid-typeof
			return;
		}

		// If we are not dealing with one of the Tribe Widgets
		// Look for widgets embedded in site builder panels, don't bail if we find one
		if (
			! $widget.is( '[id*="tribe-"]' ) &&
			(
				$widget.is( '.so-content.panel-dialog' ) &&
				! $widget.find( '[id^="widget-tribe-events-"]' )
			)
		) {
			return;
		}

		// We are dealing with a widget in the widgets menu
		if ( $widget.is( '[id*="__i__"]' ) ) {
			return;
		}

		$widget.find( '.tribe-widget-select2' ).each( tribeWidget.select2 ).trigger( 'change.select2' );

		// On change of widget fields process conditional display
		$widget.on( 'change', '.js-tribe-condition', function () {
			tribeWidget.conditional(  this, $widget );
		} );

		tribeWidget.showFilters( $widget );

		// Only happens on Widgets Admin page
		if ( !$( 'body' ).hasClass( 'wp-customizer' ) ) {
			if ( $.isNumeric( e ) || 'widget-updated' === e.type ) {
				$widget.find( '.js-tribe-condition' ).each( function() {
					// check for conditional display of fields and process after saving
					tribeWidget.conditional( this, $widget );
				} );

				$widget
					.find( '[data-depends]' )
					.trigger( 'setup.dependency' )
					.trigger( 'verify.dependency' );
			}
		}
	};

	// Configure the Widgets by default
	$( function ( event ) { // eslint-disable-line no-unused-vars
		// Prevents problems on Customizer
		if ( $( 'body' ).hasClass( 'wp-customizer' ) ) {
			return;
		}

		tribeWidget.showFilters;

		// This ensures that we set up the widgets that are already in place correctly
		$( '.tribe-widget-form' ).each( tribeWidget.setup );
	} );

	$( document )
		.on( {
			// On the Widget Actions, try to re-configure.
			'widget-added widget-updated': function ( e, widget ) {
				var $widget = $( widget );
				tribeWidget.setup( e, $widget );
				tribeWidget.showFilters( $widget );
				$widget
					.find( '[data-depends]' )
					.trigger( 'setup.dependency' )
					.trigger( 'verify.dependency' );
			}
		} )
		.on(
			'change',
			'.calendar-widget-add-filter',
			function( e ) { // eslint-disable-line no-unused-vars,max-len
				var $select = $( this );
				var $widget = $select.parents( '.tribe-widget-form' );
				var $list = $widget.find( '.calendar-widget-filter-list' );
				var $field = $widget.find( '.calendar-widget-added-filters' );
				var values = $field.val() ? JSON.parse( $field.val() ) : {};
				var term = $select.val();
				var disabled = $select.data( 'disabled' ) ? $select.data( 'disabled' ) : [];
				var options = $select.data( 'lastOptions' );

				if ( 'undefined' === typeof term ) {
					return;
				}

				var term_obj;

				if ( null === values || 'object' !== typeof values ) {
					values = {};
				}

				if ( typeof options !== 'undefined' && typeof options.results !== 'undefined' ) {
					options.results.forEach( function( group ) {
						if ( ! group.tax ) {
							return;
						}
						// If we don't have the given Taxonomy.
						if ( ! values[ group.tax.name ] ) {
							values[ group.tax.name ] = [];
						}

						group.children.forEach( function( option ) {
							if ( ! option ) {
								return;
							}

							if ( option.id != term ) { // eslint-disable-line eqeqeq
								return;
							}

							term_obj = option;
							values[ group.tax.name ].push( option.id );
						} );
					} );
				}

				if ( ! term_obj ) {
					return;
				}

				// Bail if we already have the term added.
				if (
					-1 !== $.inArray( term.id, values[ term_obj.taxonomy.name ] ) &&
					-1 !== $.inArray( term, values[ term_obj.taxonomy.name ] )
				) {
					// Remove the Selected Option.
					$select.val( '' );
					return;
				}

				// Safety net for items not in the values hash.
				if ( $list.find( '[data-term="' + term_obj.id + '"]' ).length ) {
					return;
				}

				values[ term_obj.taxonomy.name ].push( term.id );
				$field.val( JSON.stringify( values ) );

				var $link = $( '<a/>' ).attr( {
					'data-tax': term_obj.taxonomy.name,
					'data-term': term_obj.id,
					'class': 'calendar-widget-remove-filter',
					'href': '#',
				} ).text( '(remove)' );
				var $remove = $( '<span/>' ).append( $link );
				var $li = $( '<li/>' )
					.addClass( 'calendar-widget-filter-item' )
					.html( term_obj.taxonomy.labels.name + ': ' + term_obj.text )
					.append( $remove );

				$list.append( $li );

				disabled.push( term_obj.id );
				$select.data( 'disabled', disabled );
				tribeWidget.showFilters( $widget );

				// After all that remove the Opt
				$select.val( '' );
			}
		)
		.on( 'click', '.calendar-widget-remove-filter', function ( e ) {
			e.preventDefault();
			var $link = $( this ),
				$widget = $link.parents( '.tribe-widget-form' ),
				$select = $widget.find( '.calendar-widget-add-filter' ).not( '.select2-container' ),
				$field = $widget.find( '.calendar-widget-added-filters' ),
				values = $field.val() ? JSON.parse( $field.val() ) : {},
				termId = $link.data( 'term' ),
				taxonomy = $link.data( 'tax' ),
				disabled = $select.data( 'disabled' ) ? $select.data( 'disabled' ) : [];

			if ( values[ taxonomy ] ) {
				values[ taxonomy ] = _.without( values[ taxonomy ], termId.toString(), null );
			}

			// Updates the HTML field
			$field.val( JSON.stringify( values ) );

			// Updates the Select2 Exclusion
			$select.data( 'disabled', _.without( disabled, termId.toString() ) );

			$link.closest( 'li' ).remove();

			// support the customizer by triggering a false change on an element so the updated hidden field gets saved
			$widget.find( 'input[name^="widget-tribe-"]' ).trigger( 'change' );

			tribeWidget.hideFilters( $widget );
		} )
		.on( 'click', '.so-close', function( e ) { // eslint-disable-line no-unused-vars
			// Close select2 when we close a panel dialog
			$( '.calendar-widget-add-filter' ).select2( 'close' );
		} );

	// Open the Widget
	$body.on( 'click.widgets-toggle', tribeWidget.setup );

	// Pass the pagebuilder panel as the "widget" so we can set up filters correctly
	$( document ).on(
		'panelsopen',
		function ( e ) {
			$( '.so-content.panel-dialog' ).each( function() {
				var $this = $(this);
				// If we haven't already set up the widget
				if ( ! $this.hasClass( 'widget' ) ) {
					// get the ID from the title input
					var $id = $this.find( '[id^="widget-tribe-"]' ).filter( '[id$="-title"]' ).attr( 'id' );

					if ( ! $id ) {
						return;
					}

					// Set the ID and class for our target
					$id = $id.substring( 0, $id.indexOf( '-title' ) );
					$this.attr( 'id', $id ).addClass( 'widget' );

					// Set up widget
					tribeWidget.setup( e, $this );

					$( '.so-duplicate' ).on( 'click', function( e ) { // eslint-disable-line no-unused-vars
						// Close select2 when we close a panel dialog
						$( '.calendar-widget-add-filter' ).select2( 'close' );
					} );

					$( '.so-delete' ).on( 'click', function( e ) { // eslint-disable-line no-unused-vars
						// Close select2 when we close a panel dialog
						$( '.calendar-widget-add-filter' ).select2( 'close' );
					} );
				}

			} );
	} );

	$( document ).on(
		'change',
		'.fl-builder-settings-lightbox',
		function( event ) {
			tribeWidget.setup( event, $( ".tribe-widget-form" ).first() );
		}
	);
}( jQuery.noConflict(), _ ) );
