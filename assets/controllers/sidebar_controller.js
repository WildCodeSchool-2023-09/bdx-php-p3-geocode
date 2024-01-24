import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets =  ['sidebar'];
    connect()
    {
        //this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
    }

    toggle(event)
    {
      console.log(event.target.id);
        if (event.target.id === 'button-toggle-sidebar') {
            event.target.parentNode.classList.toggle('sidebar-hidden');
        }else{
          event.target.parentNode.parentNode.classList.toggle('sidebar-hidden');
        }
    }
}
