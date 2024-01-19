
window.waitCloseModal = async (time, multiple) => {
    if (!multiple) {
        const delay = ms => new Promise(res => setTimeout(res, ms));
        await delay(time * 2500);
        Livewire.emit('closeModal');
    }
}

window.fireTooltip = () => {
    document.querySelectorAll('button[data-tooltip-target]').forEach(triggerEl => {
        const targetEl = document.getElementById(triggerEl.getAttribute('data-tooltip-target'))
        const triggerType = triggerEl.getAttribute('data-tooltip-trigger');

        new Tooltip(targetEl, triggerEl, {
            triggerType
        });
    });
}
window.formatNumber = (value) => {
    value = parseFloat(value).toFixed(2);
    return Number(value).toLocaleString('en');
};

window.moveNavigation = (tabcont, direction) => {

    const totalChildrens = document.getElementsByClassName(tabcont)[0].childElementCount;
    const navigation = document.getElementsByClassName(tabcont)[0];
    const width = navigation.scrollWidth;

    const scrollChildPerClick = 3;
    const childWidth = (width / totalChildrens) * scrollChildPerClick;

    if (direction === 'left') {
        navigation.scrollLeft = navigation.scrollLeft - childWidth;
        return;
    }
    navigation.scrollLeft = navigation.scrollLeft + childWidth;
    return;
};
