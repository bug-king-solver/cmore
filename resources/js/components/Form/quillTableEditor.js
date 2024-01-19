/* eslint-disable indent */
/* eslint-disable no-param-reassign */

/**
 * Just a simple function add to  Quill editor , the ability  to create tables
 * @param {*} table
 * @returns void
 */
window.makeQuillEdtitorTable = (table) => {
    const iconWidth = 25;
    const iconHeight = 25;
    document.querySelectorAll('.ql-column-right').forEach((button) => {
        button.innerHTML = `<svg width="${iconWidth}" height="${iconHeight}" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Edit / Add_Column"> <path id="Vector" d="M5 17H8M8 17H11M8 17V14M8 17V20M14 21H15C16.1046 21 17 20.1046 17 19V5C17 3.89543 16.1046 3 15 3H13C11.8954 3 11 3.89543 11 5V11" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>`;
        button.title = 'Add column right';
        button.dataset.quillModule = 'table';
        button.addEventListener('click', () => {
            table.insertColumnRight();
        });
    });
    document.querySelectorAll('.ql-row-below').forEach((button) => {
        button.innerHTML = `<svg width="${iconWidth}" height="${iconHeight}" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Edit / Add_Row"> <path id="Vector" d="M3 14V15C3 16.1046 3.89543 17 5 17L19 17C20.1046 17 21 16.1046 21 15L21 13C21 11.8954 20.1046 11 19 11H13M10 8H7M7 8H4M7 8V5M7 8V11" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>`;
        button.title = 'Add row below';
        button.dataset.quillModule = 'table';
        button.addEventListener('click', () => {
            table.insertRowBelow();
        });
    });
    document.querySelectorAll('.ql-row-remove').forEach((button) => {
        button.innerHTML = `<svg width="${iconWidth}" height="${iconHeight}" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Edit / Delete_Row"> <path id="Vector" d="M14 16H20M21 10V9C21 7.89543 20.1046 7 19 7H5C3.89543 7 3 7.89543 3 9V11C3 12.1046 3.89543 13 5 13H11" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>`;
        button.title = 'Remove row';
        button.dataset.quillModule = 'table';
        button.addEventListener('click', () => {
            table.deleteRow();
        });
    });
    document.querySelectorAll('.ql-column-remove').forEach((button) => {
        button.innerHTML = `<svg width="${iconWidth}" height="${iconHeight}" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Edit / Delete_Column"> <path id="Vector" d="M10 21H9C7.89543 21 7 20.1046 7 19V5C7 3.89543 7.89543 3 9 3H11C12.1046 3 13 3.89543 13 5V11M19 16H13" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>`;
        button.title = 'Remove column';
        button.addEventListener('click', () => {
            table.deleteColumn();
        });
    });
    return table;
};

/* 'column-left', 'row-above' - disabled in the quill editor component */

// document.querySelectorAll('.ql-column-left').forEach((button) => {
//     button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-column-insert-left" width="${iconWidth}" height="${iconHeight}" viewBox="0 0 24 24" stroke-width="1" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
//         <path stroke="none" d="M0 0h24v24H0z" fill="none" />
//   <path d="M14 4h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1z" />
//         <line x1="5" y1="12" x2="9" y2="12" />
//         <line x1="7" y1="10" x2="7" y2="14" />
//         </svg>`;
//     button.title = 'Add column left';
//     button.dataset.quillModule = 'table';
//     button.addEventListener('click', () => {
//         table.insertColumnLeft();
//     });
// });
// document.querySelectorAll('.ql-row-above').forEach((button) => {
//     button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-row-insert-bottom" width="${iconWidth}" height="${iconHeight}" viewBox="0 0 24 24" stroke-width="1" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
//     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
//     <path d="M20 6v4a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1z" />
//     <line x1="12" y1="15" x2="12" y2="19" />
//     <line x1="14" y1="17" x2="10" y2="17" />
//   </svg>`;
//     button.title = 'Add row above';
//     button.dataset.quillModule = 'table';
//     button.addEventListener('click', () => {
//         table.insertRowAbove();
//     });
// });
