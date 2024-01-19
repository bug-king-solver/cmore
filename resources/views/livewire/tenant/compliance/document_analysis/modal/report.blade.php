<div >
    <x-modals.white title="{{ 'Report an issue' }}" btnText="Send issue Report" class="bg-[#8A8A8A] normal-case border-none">

        <div class="text-sm text-[#444]">
        {{__('The analysis is made by searching for one or more words within the document. If you think there are any inconsistencies, select the areas in question and write the terms we must look for.')}}
        </div>

        <x-inputs.checkbox label="Child labour and Young Workers" icon='found' />
        <x-inputs.checkbox label="Wages and benefits" icon='not-found' />
        <x-inputs.checkbox label="Working hours" />

        <x-inputs.checkbox label="Modern slavery"/>
        <x-inputs.checkbox label="Freedom of association and collective bargaining"/>

        <x-inputs.checkbox label="Harassment and non-discrimination"/>

        <textarea class="w-full h-11 p-2 rounded-lg bg-esg41 border-none text-sm" placeholder="Additional comments (optional)"></textarea>

    </x-modals.white>
</div>
