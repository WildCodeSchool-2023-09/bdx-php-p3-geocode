import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets =  ['sidebar', 'button'];
    connect()
    {
        if (window.location.pathname === '/admin/panel') {
            this.toggle();
        }
    }

    toggle()
    {
        this.sidebarTarget.classList.toggle('sidebar-hidden');
        this.buttonTargets.forEach((elt) => elt.classList.toggle('hidden'));
        document.getElementsByTagName('main')[0].classList.toggle('with-aside');
    }
}
