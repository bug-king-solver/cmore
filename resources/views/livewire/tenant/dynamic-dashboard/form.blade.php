<x-slot name="header">
    <x-header class="w-full fixed top-16 h-28 z-[9]"
        title="{{ isset($dashboard->name) ? __('Edit: :dashboard', ['dashboard' => $dashboard->name]) : __('Create a new dashboard') }}"
        :fixed="true">
        <x-slot name="left" >
        </x-slot>
    </x-header>
</x-slot>

@php
    $layout = $layout ?? [];
@endphp

<div class="flex w-full mt-52" x-data="{ isOpen: true, type: 'open' }">

    <x-sidebar>
        <x-reports.sidebar-tools :graphs="$availableGraphs" :text="$availableText" />
    </x-sidebar>

    <div class="w-full" :class="isOpen ? 'ml-96' : 'ml-20'">
        @if (session('success'))
            <x-alerts.success>{{ session('success') }}</x-alerts.success>
        @endif
        <div class="flex justify-between ">
            <div class="" x-data="{ dashboardname: false }">
                <div class="pt-4 flex">
                    <x-inputs.text input="text" x-on:blur="dashboardname = false" x-on:focus="dashboardname = true"
                        placeholder="{{ __('Untitled dashboard') }}"
                        class="text-md text-esg8 border !border-[#757575] !rounded " id="name" required
                        wire:model='name' />
                </div>
                <div class="text-red-500 ">
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="ml-4 pt-2 text-esg8">
                <button type="button" wire:click="cancel"
                    class="text-esg16 bg-esg4 hover:bg-esg4 border border-esg16 focus:outline-none focus:ring-esg16 font-medium rounded  text-sm px-5 py-2.5 text-center inline-flex items-center mr-4 mb-2">
                    {{ __('Cancel') }}
                </button>

                <button @click="saveData()"
                    class="text-esg4 bg-esg5 font-medium rounded  text-sm px-5 py-2.5 text-center inline-flex items-center mr-4 mb-2">
                    {{ __('Save') }}
                </button>
            </div>
        </div>

        <div class="" class="pt-4 ">
            <x-form.label label="{!! __('Description') !!}" />
            <div class="flex">
                <x-inputs.text input="text" placeholder="{{ __('Description') }}"
                    class="text-md text-esg8 border !border-[#757575] !rounded" id="description" required  wire:ignore />
            </div>
            <div class="text-red-500 ">
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <hr class="bg-[#E1E6EF] h-px my-5 border-0" />

        <div id="tooltip-add-column" role="tooltip"
            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            {{ __('Add Column') }}
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>

        <div id="tooltip-add-row" role="tooltip"
            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            {{ __('Add Row') }}
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>

        <div id="tooltip-remove-row" role="tooltip"
            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            {{ __('Remove Row') }}
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>

        <div wire:ignore id="dropzone" drag-dropzone class="grid grid-auto gap-6 relative h-auto w-full">

            @foreach ($layout as $rowIndex => $row)
                <div class="border border-1 !border-esg6 border-dashed rounded flex flex-col relative h-full w-full gap-3 p-4"
                    id="mainDiv-{{ $rowIndex + 1 }}">

                    <div class="flex flex-row items-center justify-end">
                        <x-buttons.btn-icon class="bg-esg0 flex-row gap-2" data-tooltip-target="hover"
                            data-tooltip-target="tooltip-add-column" @click="addColumn('row-{{ $rowIndex + 1 }}')">
                            <x-slot name="buttonicon">
                                @include('icons.add-column')
                            </x-slot>
                        </x-buttons.btn-icon>

                        <x-buttons.btn-icon class="bg-esg0 flex-row gap-2" data-tooltip-target="hover"
                            data-tooltip-target="tooltip-add-row" @click="addRow()">
                            <x-slot name="buttonicon">
                                @include('icons.add-row')
                            </x-slot>
                        </x-buttons.btn-icon>

                        <x-buttons.btn-icon class="bg-esg0 flex-row gap-2" data-tooltip-target="hover"
                            data-tooltip-target="tooltip-remove-row" @click="removeRow('row-{{ $rowIndex + 1 }}')">
                            <x-slot name="buttonicon">
                                @include('icons.trash-blue')
                            </x-slot>
                        </x-buttons.btn-icon>
                    </div>

                    <div class="flex h-full w-full">

                        <div class="w-full row grid gap-4 h-auto grid-cols-{{ sizeOf($row) <= 2 ? sizeOf($row) : 2 }}"
                            id="row-{{ $rowIndex + 1 }}">

                            @foreach ($row as $colIndex => $col)
                                <div drag-dropzone-cols class="flex flex-col h-full w-full relative rounded"
                                    id='row-{{ $rowIndex + 1 }}-col-{{ $colIndex + 1 }}'>

                                    <div
                                        class="p-2 border-1 border !border-[#B1B1B1] min-h-[100px] min-w-[300px] relative rounded-md flex flex-col items-center">
                                        <div class="flex justify-end w-full">
                                            @if ($col['type'] == 'graph')
                                                @php $data = json_encode(['dashboard' => $dashboard, 'rowIndex' => $rowIndex, 'colIndex' => $colIndex]); @endphp
                                                <x-buttons.edit modal="report.modals.chart-customization"
                                                    :data="$data" />
                                            @endif
                                            <x-buttons.trash
                                                @click="removeColumn('row-{{ $rowIndex + 1 }}-col-{{ $colIndex + 1 }}')" />
                                        </div>
                                        @php
                                            $chart = App\Models\Chart::where('slug', $col['value'])
                                                ->orWhere('slug', $col['type'])
                                                ->first();
                                            $structure = $chart->structure ?? [];
                                            $attributes = $structure['attributes'] ?? '';
                                        @endphp

                                        <span {!! $attributes ?? 'class="w-full text-center "' !!}>
                                            @if ($col['type'] != 'graph')
                                                {{ !empty($col['value']) ? $col['value'] : $chart->name }}
                                            @else
                                                {{ $chart->name }}
                                            @endif
                                        </span>


                                        <input type="hidden" value="{{ $col['value'] }}"
                                            name="row[{{ $rowIndex + 1 }}][{{ $colIndex + 1 }}]">

                                        <input type="hidden" value="{{ $col['type'] }}"
                                            name="row[{{ $rowIndex + 1 }}][{{ $colIndex + 1 }}]">

                                        <input type="hidden"
                                            value="{{ !empty($col['structure']) ? json_encode($col['structure']) : '' }}"
                                            name="row[{{ $rowIndex + 1 }}][{{ $colIndex + 1 }}]">


                                        @if ($col['type'] != 'graph')
                                            <input type="text"
                                                value="{{ !empty($col['value']) ? $col['value'] : $chart->name }}"
                                                class="border-t-0 border-r-0 border-l-0 border-b border-[#757575] text-[##B1B1B1] rounded-md block"
                                                name="row[{{ $rowIndex + 1 }}][{{ $colIndex + 1 }}]"
                                                placeholder="{{ __('Insert a new text') }}">
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            @endforeach
            <div id="empty-dashboard" class="text-center @if (count($layout)) hidden @endif">
                <span id="emptyLines" class="text-center text-esg8">
                    {{ __('To start building your dashboard click on the button below') }}
                </span>
                <button id="addRowButton" class="flex relative mt-5 mx-auto p-2 bg-[#153A5B] text-[#FFFF] rounded"
                    @click="addRow()">
                    {{ __('Start') }}
                </button>
            </div>
        </div>

    </div>
</div>



<div class="border border-1 !border-esg6 border-dashed rounded hidden flex flex-col relative h-full w-full gap-3  p-4"
    id="templateDiv">

    <div class="flex flex-row items-center justify-end">
        <x-buttons.btn-icon class="bg-esg0 flex-row gap-2" data-tooltip-target="hover"
            data-tooltip-target="tooltip-add-column" @click="addColumn('row-0')">
            <x-slot name="buttonicon">
                @include('icons.add-column')
            </x-slot>
        </x-buttons.btn-icon>

        <x-buttons.btn-icon class="bg-esg0 flex-row gap-2" data-tooltip-target="hover"
            data-tooltip-target="tooltip-add-row" @click="addRow()">
            <x-slot name="buttonicon">
                @include('icons.add-row')
            </x-slot>
        </x-buttons.btn-icon>

        <x-buttons.btn-icon class="bg-esg0 flex-row gap-2" data-tooltip-target="hover"
            data-tooltip-target="tooltip-remove-row" @click="removeRow('row-0')">
            <x-slot name="buttonicon">
                @include('icons.trash-blue')
            </x-slot>
        </x-buttons.btn-icon>
    </div>

    <div class="flex h-full w-full">
        <div class="w-full row grid gap-4 h-auto grid-cols-1" id="row-0">

            <div drag-dropzone-cols class="columnTemplate flex flex-col h-full w-full relative rounded "
                id="row-0-col-0">

                <div
                    class="p-2 border-1 border !border-[#B1B1B1] min-h-[100px] min-w-[300px] relative rounded-md flex flex-col items-center">
                    <div class="flex justify-end w-full">
                        <x-buttons.trash @click="removeColumn('row-0-col-0')" />
                    </div>
                    <span class="w-full text-center ">
                        {!! __('Select and drag an element from the side menu') !!}
                    </span>
                    <input type="hidden" value=""
                        class="border-t-0 border-r-0 border-l-0 border-b border-[#757575] text-[##B1B1B1] rounded-md block">
                    <input type="hidden" value="">
                    <input type="hidden" value="">
                </div>
            </div>
        </div>
    </div>

</div>


@push('child-scripts')
    <script nonce="{{ csp_nonce() }}">
        let rootTexts = document.querySelector('[drag-root-text]');

        rootTexts.querySelectorAll('[drag-item-text]').forEach(el => {
            el.addEventListener('dragstart', e => {
                var name = el.getAttribute("name");
                var slug = el.getAttribute("slug");

                var structure = JSON.parse(el.getAttribute("struct"));
                var struct = structure["attributes"];

                e.dataTransfer.setData('Text', name);
                e.dataTransfer.setData('Type', "text");
                e.dataTransfer.setData('slug', slug);
                e.dataTransfer.setData('structure', struct)
            })
        });

        let rootGraphs = document.querySelector('[drag-root-graphs]');
        rootGraphs.querySelectorAll('[drag-item-graph]').forEach(el => {
            el.addEventListener('dragstart', e => {
                var name = el.getAttribute("name");
                var slug = el.getAttribute("slug");
                var placeholder = el.getAttribute("placeholder");

                e.dataTransfer.setData('Text', name);
                e.dataTransfer.setData('Type', "graph");
                e.dataTransfer.setData('slug', slug);
                e.dataTransfer.setData('placeholder', placeholder);
            })
        });

        let root2 = document.querySelector('[drag-dropzone]');
        root2.querySelectorAll('[drag-dropzone-cols]').forEach(el => {
            el.addEventListener('dragenter', e => {
                e.preventDefault();
            })
        })
        root2.addEventListener('dragenter', e => {
            e.preventDefault();
        })
        root2.addEventListener('dragover', e => {
            e.preventDefault();
        })

        function saveData() {
            var mainDivs = document.querySelectorAll(`div[id^="mainDiv-"]`);
            let hasInvalidData = false;
            let msg = false;
            if (mainDivs.length > 0) {
                const layoutArray = [];
                Array.from(mainDivs).forEach((rowDiv, rowIndex) => {
                    const colDivs = rowDiv.querySelectorAll('[drag-dropzone-cols]');
                    layoutArray[rowIndex] = [];
                    colDivs.forEach((colDiv, colIndex) => {
                        const inputs = colDiv.querySelectorAll('input');
                        layoutArray[rowIndex][colIndex] = {};
                        inputs.forEach((input, index) => {

                            var data = input.value;

                            if (!data && index < 2) {
                                hasInvalidData = true;
                                msg =
                                    '{{ __('To save the dashboard, you need to add a side menu item in the empty columns or rows') }}';
                                return;
                            }

                            if (index == 0) {
                                layoutArray[rowIndex][colIndex].value = data;
                            } else if (index == 1) {
                                layoutArray[rowIndex][colIndex].type = data;
                            } else if (index == 2) {
                                layoutArray[rowIndex][colIndex].structure = data;
                            }
                        });
                    });
                });

                if (!hasInvalidData) {
                    @this.set("layout", layoutArray)
                    @this.call("save")
                }
            } else {
                msg = '{{ __('To save the dashboard you must select a row and a side menu item') }}';
            }
            if (msg) {
                window.livewire.emit('openModal', 'modals.notification', {
                    'data': {
                        type: 'error',
                        title: '{{ __('Error') }}',
                        message: msg,
                    }
                });
            }
        }

        function startListener() {
            root2.querySelectorAll('[drag-dropzone-cols]').forEach(el => {

                if (!el.hasAttribute('listening')) {
                    el.addEventListener('drop', e => {

                        e.preventDefault();

                        const data = e.dataTransfer.getData("text");
                        const type = e.dataTransfer.getData("type");
                        const slug = e.dataTransfer.getData("slug");
                        const structure = e.dataTransfer.getData("structure");

                        if (type == 'text') {
                            const regex = /(?<=\')(.*?)(?=\')/;
                            var objClass = (regex.exec(structure)[0]).split(" ");
                            var col = document.getElementById(el.id);
                            var elementPreview = col.getElementsByClassName("elementPreview")[0];

                            if (elementPreview !== undefined) {
                                elementPreview.remove();
                            }

                            el.getElementsByTagName('span').item(0).classList.add(...objClass);
                            el.getElementsByTagName('span').item(0).classList.add("!font-normal");
                            el.getElementsByTagName('span').item(0).textContent = data;
                            el.getElementsByTagName('span').item(0).style.position = "relative";

                            el.getElementsByTagName('input').item(1).type = "hidden";
                            el.getElementsByTagName('input').item(1).value = slug;

                            el.getElementsByTagName('input').item(0).type = "text";
                            el.getElementsByTagName('input').item(0).placeholder = "Insira texto !";

                        } else {
                            const placeholder = e.dataTransfer.getData("placeholder");

                            el.getElementsByTagName('input').item(0).type = "hidden";
                            el.getElementsByTagName('input').item(0).value = slug;

                            el.getElementsByTagName('span').item(0).removeAttribute("hidden");
                            el.getElementsByTagName('span').item(0).innerHTML = data +
                                '<img class="inline-block" draggable="false" src="' + placeholder +
                                '" alt="" style="max-height: 100px;">';

                            el.getElementsByTagName('input').item(1).type = "hidden";
                            el.getElementsByTagName('input').item(1).value = type;
                        }
                    })
                }
            });

            fixInputsName();
        }

        function addRow(data = null) {
            var divEmpty = document.getElementById("empty-dashboard");
            if (typeof divEmpty !== 'undefined' && divEmpty !== null) {
                divEmpty.style.display = "none";
            }

            /** check the last DIV ID and increment it by 1 */
            var divs = Array.from(document.querySelectorAll('[id^="mainDiv-"]'));
            var lastDiv = divs.pop();
            var childrenNumber = 0;
            if (typeof lastDiv !== 'undefined' && lastDiv !== null) {
                var id = lastDiv.getAttribute('id');
                var childrenNumber = id.split('-')[1] || 0;
            }

            childrenNumber = Number(childrenNumber) + 1;

            var templateDiv = document.getElementById('templateDiv');
            var mainDiv = templateDiv.outerHTML;
            var mainDiv = mainDiv.replace(/row-0/g, 'row-' + childrenNumber);
            var mainDiv = mainDiv.replace(/templateDiv/g, 'mainDiv-' + childrenNumber);


            const parentDiv = document.getElementById('dropzone');
            parentDiv.insertAdjacentHTML('beforeend', mainDiv);

            newDiv = document.getElementById('mainDiv-' + childrenNumber);
            newDiv.classList.remove("hidden");

            //seach for the first button and add name[row][col]
            var button = newDiv.getElementsByTagName("button");
            button[0].setAttribute("name", "row[" + childrenNumber + "][1]");

            var colDiv = newDiv.querySelector('.columnTemplate');
            colDiv.classList.remove("columnTemplate");

            startListener();

            document.querySelectorAll('[data-tooltip-target]').forEach(function(triggerEl) {
                const targetEl = document.getElementById(triggerEl.getAttribute('data-tooltip-target'));
                const triggerType = triggerEl.getAttribute('data-tooltip-trigger');
                const placement = triggerEl.getAttribute('data-tooltip-placement');
                new Tooltip(targetEl, triggerEl, {
                    placement: placement ? placement : 'top',
                    triggerType: triggerType ? triggerType : 'hover'
                });
            });

        }

        function addColumn(rowId) {

            var row = document.getElementById(rowId);
            const rowChildrens = row.children.length || 0;

            if (rowChildrens == 4) {

                window.livewire.emit('openModal', 'modals.notification', {
                    'data': {
                        type: 'warning',
                        title: '{{ __('Warning') }}',
                        message: '{{ __('You can only add up to 4 columns per row') }}',
                    }
                });
                return;
            }

            var colDiv = document.getElementsByClassName('columnTemplate').item(0).cloneNode(true);
            colDiv.id = `${rowId}-col-${Number(rowChildrens + 1)}`;
            colDiv.classList.remove("columnTemplate");

            var newDiv = colDiv.outerHTML.replace(/row-0-col-0/g, `${rowId}-col-${Number(rowChildrens + 1)}`);
            row.insertAdjacentHTML('beforeend', newDiv);

            var inputs = Array.from(colDiv.getElementsByTagName("input")).filter(input => input.type === 'hidden');

            inputs.forEach((el, index) => {
                var rowNumber = row.id.replace('row-', '');
                el.name = "row[" + rowNumber + "][" + Number(rowChildrens) + "]";
            });

            updateGridLayout(rowId);
            startListener();
        }

        function removeRow(rowId) {
            var row = document.getElementById(rowId);
            const id = rowId.replace('row-', '');
            const mainDiv = document.getElementById('mainDiv-' + id);
            mainDiv.remove();
            var divs = Array.from(document.querySelectorAll('[id^="mainDiv-"]'));
            var divEmpty = document.getElementById("empty-dashboard");
            if (typeof divEmpty !== 'undefined' && divEmpty !== null && divs.length == 0) {
                divEmpty.style.display = "block";
            }
        }

        function removeColumn(colId) {
            const div = document.getElementById(colId);
            const parts = colId.split('-col-');
            div.remove();
            const rowId = parts[0];

            updateGridLayout(rowId);
        }

        function updateGridLayout(rowId) {
            var row = document.getElementById(rowId);
            let rowChildrens = 0;

            for (let i = 0; i < row.children.length; i++) {
                const child = row.children[i];
                if (child.hasAttribute('drag-dropzone-cols')) {
                    rowChildrens++;
                }
            }

            for (let i = row.classList.length - 1; i >= 0; i--) {
                const className = row.classList[i];
                if (className.startsWith('grid-cols-')) {
                    row.classList.remove(className);
                }
            }

            const gridSize = Number(rowChildrens) > 2 ? 2 : Number(rowChildrens);
            row.classList.add('grid-cols-' + gridSize);

            return row;
        }

        function fixInputsName() {
            var mainDivs = document.querySelectorAll(`div[id^="mainDiv-"]`);
            if (mainDivs.length > 0) {
                Array.from(mainDivs).forEach((rowDiv, rowIndex) => {
                    const colDivs = rowDiv.querySelectorAll('[drag-dropzone-cols]');
                    colDivs.forEach((colDiv, colIndex) => {
                        const inputs = colDiv.querySelectorAll('input');
                        inputs.forEach((input, index) => {
                            input.name = "row[" + (rowIndex + 1) + "][" + (colIndex + 1) + "]";
                        });
                    });
                });
            }
        }

        document.addEventListener("dashboardChartSaved", event => {
            var mainDivs = document.querySelectorAll(`div[id^="mainDiv-"]`);
            if (mainDivs.length > 0) {
                const layoutArray = [];
                Array.from(mainDivs).forEach((rowDiv, rowIndex) => {
                    const colDivs = rowDiv.querySelectorAll('[drag-dropzone-cols]');
                    layoutArray[rowIndex] = [];
                    colDivs.forEach((colDiv, colIndex) => {
                        const inputs = colDiv.querySelectorAll('input');
                        layoutArray[rowIndex][colIndex] = {};
                        inputs.forEach((input, index) => {
                            if (index == 2) {
                                const inputName = input.name;
                                const inputNameParts = inputName.split('][');
                                const rowNumber = parseInt(inputNameParts[0].replace('row[',
                                    '')) - 1;
                                const colNumber = parseInt(inputNameParts[1].replace('][',
                                    '').replace(']', '')) - 1;

                                if (rowNumber == event.detail.rowIndex && colNumber == event
                                    .detail.colIndex) {
                                    input.value = JSON.stringify(event.detail.structure);
                                }
                            }
                        })
                    })
                })
            }
        });
    </script>
@endpush
