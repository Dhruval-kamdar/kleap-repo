jQuery.fn.extend({
    createRepeater: function (options = {}) {
        var hasOption = function (optionKey) {
            return options.hasOwnProperty(optionKey);
        };
        var option = function (optionKey) {
            return options[optionKey];
        };
        var addItem = function (items, key, fresh = true) {
            var itemContent = items;
            var group = itemContent.data("group");
            var item = itemContent;
            var input = item.find('input,select');
            input.each(function (index, el) {
                var attrName = jQuery(el).data('name');
                var skipName = jQuery(el).data('skip-name');
                if (skipName != true) {
                   // jQuery(el).attr("name", group + "[" + key + "]" + attrName);
                } else {
                    if (attrName != 'undefined') {
                     //   jQuery(el).attr("name", attrName);
                    }
                }
                if (fresh == true) {
                    jQuery(el).attr('value', '');
                }
            })
            var itemClone = items;
            /* Handling remove btn */
            var removeButton = itemClone.find('.remove-btn');
            jQuery("<div class='items'>" + itemClone.html() + "<div/>").appendTo(repeater);
        };
        /* find elements */
        var repeater = this;
        var items = repeater.find(".items");
        var key = 0;
        var addButton = repeater.find('.repeater-add-btn');

        items.each(function (index, item) {
            items.remove();
            if (hasOption('showFirstItemToDefault') && option('showFirstItemToDefault') == true) {
                addItem(jQuery(item), key);
                key++;
            } else {
                if (items.length > 1) {
                    addItem(jQuery(item), key);
                    key++;
                }
            }
        });

        /* handle click and add items */
        addButton.on("click", function () {
            addItem(jQuery(items[0]), key);
            key++;
        });
    }
});
