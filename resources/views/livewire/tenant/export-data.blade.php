<div>
    <div class="flex flex-col gap-2 mb-9">
        <span class="text-esg29 text-base">{!! __('Data extraction') !!}</span>
        <span
            class="text-esg8 text-xs">{{ __('Choose a module to download the information from. Keep in mind that you can\'t export deleted user data.') }}</span>
    </div>

    <div>
        <x-settings.export-data name="Companies"
            description="Contain registration data about all the companies on this tenant."
            click="exportToCSV('companies')" />

        <x-settings.export-data name="Assets"
            description="Information about all assets, including manually and imported data."
            click="exportToCSV('assets')" />

        <x-settings.export-data name="Targets" description="Your targets, groups, goals, associated tasks and progress."
            click="exportToCSV('targets')" />

        <x-settings.export-data name="Tasks" description="Data for your open and completed tasks."
            click="exportToCSV('tasks')" />

        <x-settings.export-data name="Tags"
            description="Your created tags and their association throughout the system." click="exportToCSV('tags')" />

        <x-settings.export-data name="Users"
            description="All registered users and their association throughout the system."
            click="exportToCSV('users')" />

        <x-settings.export-data name="Roles" description="All roles information and its permissions"
            click="exportToCSV('roles')" />
    </div>
</div>
