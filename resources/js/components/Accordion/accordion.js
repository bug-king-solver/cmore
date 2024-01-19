window.setItem = (item, parent) => {
    const button = item.querySelector('.accordion-button');
    const content = item.querySelector('.accordion-content');
    const header = item.querySelector('.accordion-header');
    const styles = window.getComputedStyle(item);
    const paddingHeight = parseFloat(styles.paddingTop) + parseFloat(styles.paddingBottom);
    if (!button.hasClickEvent) {
        button.hasClickEvent = true;
        item.style.maxHeight = header.offsetHeight + paddingHeight + "px";
        item.style.minHeight = "56px";
        button.addEventListener('click', () => {
            button.clicked = !button.clicked;
            item.classList.toggle('active');
            content.style.opacity = 1
            content.style.visibility = 'visible'
            let h;
            if (!button.clicked) {
                h = header.offsetHeight + paddingHeight + "px";
            } else {
                h = calculateTotalHeight(parent) + content.offsetHeight + "px";
            }
            item.style.maxHeight = h;
            if (parent) {
                parent.style.maxHeight = calculateTotalHeight(null) + content.offsetHeight + "px";
            }
        });
    } else {
        let h;
        if (!button.clicked) {
            h = header.offsetHeight + paddingHeight + "px";
        } else {
            h = calculateTotalHeight(parent) + content.offsetHeight + "px";
        }
        item.style.maxHeight = h;
        if (parent) {
            parent.style.maxHeight = calculateTotalHeight(null) + content.offsetHeight + "px";
        }
    }
}

window.calculateTotalHeight = (container) => {
    const items = container ? container.querySelectorAll('.accordion-item') : document.querySelectorAll('.accordion-item');
    let totalHeight = 0;

    items.forEach(item => {
        totalHeight += item.offsetHeight;
    });
    return totalHeight;
}

window.loadAccordion = () => {
    const accContainers = document.querySelectorAll(".accordion-container");

    if (accContainers) {
        accContainers.forEach(container => {
            const items = container.querySelectorAll(':scope > .accordion-item');
            items.forEach((item) => {
                const childs = item.querySelectorAll('.accordion-child');
                if (childs) {
                    childs.forEach(element => {
                        const childItems = element.querySelectorAll('.accordion-item');
                        childItems.forEach((childItem) => {
                            setItem(childItem, item);
                        });
                    });
                }
                setItem(item);
            });
        });
    }
}


document.addEventListener("DOMContentLoaded", function () {
    const loader = document.querySelector(".loader-overlay");

    if (loader) {
        loader.style.visibility = "hidden"
        loader.style.opacity = "0"
    }

    loadAccordion();

    const accordionButtons = document.querySelectorAll(".loadAccordion");
    if (accordionButtons) {
        accordionButtons.forEach(button => {
            button.addEventListener('click', () => {
                setTimeout(() => {
                    loadAccordion();
                }, 200);
            });
        });
    }
});


function handleIntersection(entries, observer) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
            observer.unobserve(entry.target);
        }
    });
}

const observer = new IntersectionObserver(handleIntersection, {
    threshold: .3
});

const yourComponent = document.querySelectorAll('.enter-transition');

yourComponent.forEach(element => {
    observer.observe(element);
});
