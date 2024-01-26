import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets =  ['sidebar', 'button'];
    connect()
    {
        //this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
    }

    toggle(event)
    {
        this.sidebarTarget.classList.toggle('sidebar-hidden');
        this.buttonTargets.forEach((elt) => elt.classList.toggle('hidden'));
        document.getElementsByTagName('main')[0].classList.toggle('with-aside');
    }
}
