$(document).ready(function () {

    $('#selectAll').on('change', function () {
      $('.rowCheckbox').prop('checked', $(this).is(':checked'));
    });

    $(document).on('change', '.rowCheckbox', function () {
      const total = $('.rowCheckbox').length;
      const checked = $('.rowCheckbox:checked').length;
      $('#selectAll').prop('checked', total === checked);
    });
  
});