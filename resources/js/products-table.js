$(document).ready(function () {

    /**
     * Работа с таблицей продуктов
     *
     * @param {Object} params
     * @param {String} params.token - csrf токен
     * @param {String} params.url - блок редактирования цены
     */
    $.fn.productsTable = function (params) {

        const $self = $(this);
        const token = $self.data(params.token);
        const url = $self.data(params.url);

        console.log(token);
        console.log(url);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });

        $self.find('.price-edit').editable({
            url: url,
            title: 'Update',
            success: function (response, newValue) {
                console.log('Updated', response)
            }
        });

        return this;
    };

    const init = function () {
        $('#products-table-module').productsTable({
            token: 'csrfToken',
            url: 'urlApiUpdatePrice'
        });
    };

    if ($('#products-table-module').length > 0) {
        init();
    }
});


