
@section('scripts')
<script>

    const buttonAdd2 = document.querySelector('#addicon2');


    buttonAdd2.addEventListener('click', function () {
      sidebarcust.classList.add('show')
      //  container.classList.add('move');
       backdrop.classList.add('show');
    })

</script>
<script>
    $(document).ready(function () {
        var $table = $('#allDrivers')

        $('#allDrivers').tablesorter(
            {theme: 'blue',
		    selectorHeaders: '> thead th span, > thead td span',
            // jQuery selector of content within selectorHeaders
            // that is clickable to trigger a sort.
            selectorSort: "span"
        });


        $('#allDrivers').dragtable({
            persistState: function (table) {
                if (!window.sessionStorage) return;
                var ss = window.sessionStorage;
                table.el.find('th').each(function (i) {
                    if (this.id != '') {
                        table.sortOrder[this.id] = i;
                    }
                });
                ss.setItem('tableorderdriver', JSON.stringify(table.sortOrder));
                $table.find('thead .tablesorter-header-inner').contents().unwrap();
                $table.trigger('updateAll', false);
            },
            restoreState: eval('(' + window.sessionStorage.getItem('tableorderdriver') + ')')
        });


        $('#allDrivers').dragtable({
            dragHandle: ".table-handle",
            dragaccept: '.accept'
        });

    });



    function changePagination(e) {
        $("#empFOrm").submit();
    }
</script>
<script>
    $(document).ready(function () {
        $('#allDrivers .icon').on('click', function () {
            var parent = $(this).parent().attr('thname');
            $(this).parent().fadeOut(400);
            $("td."+parent).fadeOut(400);
            $("#columnSelect  ."+parent).removeAttr('checked');
        })

        let btnAdd = document.getElementById('addicon');
        let sbCust = document.querySelector('.sidebarcustom');
        let bckDrop = document.querySelector('.backdrop');
        let cancelbtn = document.querySelector('#cancelbtn');




        btnAdd.addEventListener('click', function () {
            sbCust.classList.add('show')
            bckDrop.classList.add('show');
        })

        bckDrop.addEventListener('click', function () {
            sbCust.classList.remove('show')
            bckDrop.classList.remove('show');
        })

        cancelbtn.addEventListener('click', function () {
            sbCust.classList.remove('show')
            bckDrop.classList.remove('show');
        })



        $('').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            // var formData = $(form).serializeArray();
            $.ajax({
                url: '{{ route("all-employee-drivers") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: form.serialize(),
                success: function (data) {

                    if (data.status == '1') {

                        $('#update').modal('show');
                    }

                }

            });
        });









    })
</script>
<script>
    $('.employeeTabBtn').on('click', function(e){
        e.preventDefault();
        $('#tabName').val("employees");
    })

    $('.archivedTabBtn').on('click', function(e){
        e.preventDefault();
        $('#tabName').val("archived");
    })
</script>
<script>
    $(document).ready(function () {
        var $table = $('#allArchivedDriver')

        $('#allArchivedDriver').tablesorter(
            {theme: 'blue',
		    selectorHeaders: '> thead th span, > thead td span',
            // jQuery selector of content within selectorHeaders
            // that is clickable to trigger a sort.
            selectorSort: "span"
        });


        $('#allArchivedDriver').dragtable({
            persistState: function (table) {
                if (!window.sessionStorage) return;
                var ss = window.sessionStorage;
                table.el.find('th').each(function (i) {
                    if (this.id != '') {
                        table.sortOrder[this.id] = i;
                    }
                });
                ss.setItem('tableorderarch', JSON.stringify(table.sortOrder));
                $table.find('thead .tablesorter-header-inner').contents().unwrap();
                $table.trigger('updateAll', false);
            },
            restoreState: eval('(' + window.sessionStorage.getItem('tableorderarch') + ')')
        });


        $('#allArchivedDriver').dragtable({
            dragHandle: ".table-handle",
            dragaccept: '.accept'
        });

    });

</script>
@endsection
