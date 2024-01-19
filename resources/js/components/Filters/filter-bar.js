/* eslint-disable no-param-reassign */
/* eslint-disable no-undef */
window.updateFiltersValues = (queryName, value) => {
  Livewire.emit('updateFiltersValues', queryName, value);
};

window.removeItemFromFilter = (queryName, value, valueKey, component) => {
  if (component === 'date-between') {
    const dateVar = window[queryName];
    dateVar.clear();
  }
  if (component === 'select') {
    const select = document.getElementsByName(`filter_${queryName}`);
    if (select.length === 0) return;
    const control = select[0].tomselect;
    control.removeItem(valueKey);
  }
  Livewire.emit('removeItemFromFilter', queryName, value, component);
};

window.updateSelectFilterValues = (el, queryName) => {
  const elementsToRemove = document.querySelectorAll('[data-ts-item]');
  elementsToRemove.forEach((element) => {
    element.style.display = 'none';
  });
  return updateFiltersValues(queryName, el.tomselect.getValue());
};

window.updateDateBetweenFilterValues = (queryName, date) => {
  const betweenDate = date.value;
  /** se nao possui a palavra TO em betweenDate , return; */
  if (!betweenDate.includes('to')) {
    return null;
  }

  let [startDate, endDate] = betweenDate.split('to');
  startDate = startDate.trim();
  endDate = endDate.trim();
  if (startDate && endDate) {
    return updateFiltersValues(queryName, [startDate, endDate]);
  }
  if (!startDate && !endDate) {
    return updateFiltersValues(queryName, null);
  }

  return null;
};

window.openTomSelect = (filterName, selectName) => {
  const select = document.getElementsByName(selectName);
  if (select.length === 0) return;
  const control = select[0].tomselect;
  control.open();
  /** .ts-control ( only visible ) apply width ==  filterDivWidth */
  const filterDiv = document.querySelectorAll(`div[data-scoped="${filterName}"]`);
  if (filterDiv.length === 0) return;
  let filterDivWidth = filterDiv[0].offsetWidth;
  document.querySelectorAll(`[data-scoped="${filterName}"] div.ts-dropdown`)[0].style.left = `-${filterDivWidth}px`;
  filterDivWidth = filterDivWidth > 150 ? filterDivWidth : 150;
  document.querySelectorAll(`[data-scoped="${filterName}"] div.ts-dropdown`)[0].style.width = `${filterDivWidth}px`;
};

window.openDateBetweenCalendar = (filterName) => {
  const dateVar = window[filterName];
  if (typeof dateVar === 'undefined') return;
  dateVar.open();
};
