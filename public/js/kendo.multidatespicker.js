$(function() {
  var day = 86400000;
  var today = new Date();
  //time is set to midnight for correct comparison with values from calendar
  today.setHours(0, 0, 0, 0);

  //data source
  var selectedDates = [
  ];

  //DatePicker with multiselection
  initPicker($('#not_available_date').kendoDatePicker(), selectedDates);

  //Calendar with multiselection
  //initCalendar($("#calendar").kendoCalendar(), selectedDates.slice());
});

function initPicker(picker, selectedDates) {
  var isInit = false;
  var kendoPicker = picker.data('kendoDatePicker');

  kendoPicker.bind('open', function() {
    if (!isInit) {
      //assuming that corresponding calendar widget has id 'picker_dateview'
      var calendar = $('#' + picker.attr('id') + '_dateview > .k-calendar');

      initCalendar(calendar, selectedDates, function() {
        updatePicker(picker, selectedDates);
      });

      isInit = true;
    }
  });

  picker.on('input change blur', function() {
    updatePicker(picker, selectedDates);
  });

  updatePicker(picker, selectedDates);
}

function initCalendar(calendar, selectedDates, onUpdate) {
  var kendoCalendar = calendar.data('kendoCalendar');

  kendoCalendar.bind('navigate', function() {
    setTimeout(function() {
      updateCalendar(calendar, selectedDates);
    }, 0);
  });

  updateCalendar(calendar, selectedDates);

  calendar.on('click', function(event) {
    var cell = $(event.target).closest('td');
    var isClickedOnDayCell = cell.length !== 0 && isDayCell(cell);

    if (isClickedOnDayCell) {
      var date = dateFromCell(cell).getTime();
      var isDateAlreadySelected = selectedDates.some(function(selected) {
        return selected === date;
      });

      if (isDateAlreadySelected) {
        selectedDates.splice(selectedDates.indexOf(date), 1);

      } else {
        selectedDates.push(date);
      }

      updateCell(cell, selectedDates);

      if (onUpdate !== undefined) {
        onUpdate();
      }
    }
  });
}

function updatePicker(picker, selectedDates) {
  var datesString = selectedDates.sort().reduce(function(acc, selected, index) {
    var date = new Date(selected);
    var formattedDate = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();

    return acc + formattedDate + (index === (selectedDates.length - 1) ? '' : ', ');
  }, '');

  picker.val(datesString);
}

function updateCalendar(calendar, selectedDates) {
  calendar.find('td > a').parent().each(function(i, item) {
    var cell = $(item);

    if (isDayCell(cell)) {
      updateCell(cell, selectedDates);
    }
  });
}

function updateCell(cell, selectedDates) {
  var isCellSelected = selectedDates.some(function(selected) {
    return selected === dateFromCell(cell).getTime();
  });

  if (isCellSelected) {
    cell.addClass('selected');

  } else {
    cell.removeClass('selected');
  }
}

function isDayCell(cell) {
  return /^\d{1,2}$/.test(cell.find('a').text());
}

function dateFromCell(cell) {
  return new Date(convertDataValue(cell.find('a').attr('data-value')));
}

//convert from 'YYYY/MM/DD', where MM = 0..11
function convertDataValue(date) {
  var regex = /\/(\d+)\//;
  var month = +date.match(regex)[1] + 1;

  return date.replace(regex, '/' + month + '/');
}
