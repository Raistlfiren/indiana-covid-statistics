import Choices from "choices.js";
import './dashboard/sparkline/case';
import './dashboard/sparkline/test';
import './dashboard/sparkline/death';
import './dashboard/line/case';
import './dashboard/line/death';
import './dashboard/line/test';
import './dashboard/bar/sex';
import './dashboard/bar/age';
import './dashboard/bar/race';
import './dashboard/bar/ethnicity';
import './dashboard/donut/bed';
import './dashboard/donut/vent';

let countySelector = document.querySelector("#countySelector");
const countyChoices = new Choices(countySelector, {
    classNames: {
        containerInner: 'choices__inner dashboard-date'
    }
});

countyChoices.passedElement.element.addEventListener(
    'change',
    function(event) {
        if (typeof(Storage) !== "undefined") {
            let county = event.detail.value;
            localStorage.setItem('county', county);
            window.location = window.homeRoute + county;
        }
    }
)

if (typeof(Storage) !== "undefined") {
    let selectedCounty = localStorage.getItem('county');
    if (selectedCounty !== null) {
        window.selectedCounty = selectedCounty;
        countyChoices.setValue([selectedCounty]);
        if (window.location.href === window.homeRoute) {
            window.location = window.homeRoute + selectedCounty;
        }
    }
}