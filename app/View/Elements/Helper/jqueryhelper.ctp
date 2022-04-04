<script>
 $.postJSON = function (url, data, callback) {
                data.csrftoken = '<?php echo $this->Session->read("csrftoken"); ?>';
                return jQuery.ajax({
                    'type': 'POST',
                    'url': url,
                    'contentType': 'application/json',
                    'data': JSON.stringify(data),
                    'dataType': 'json',
                    'success': callback
                });
            };
            
            
            
            
            



    var sortSelect = function (select, attr, order) {
        if (attr === 'text') {
            if (order === 'asc') {
                $(select).html($(select).children('option').sort(function (x, y) {
                    return $(x).text().toUpperCase() < $(y).text().toUpperCase() ? -1 : 1;
                }));
                $(select).get(0).selectedIndex = 0;
                // e.preventDefault();
            }// end asc
            if (order === 'desc') {
                $(select).html($(select).children('option').sort(function (y, x) {
                    return $(x).text().toUpperCase() < $(y).text().toUpperCase() ? -1 : 1;
                }));
                $(select).get(0).selectedIndex = 0;
                //   e.preventDefault();
            }// end desc
        }

    };
</script>