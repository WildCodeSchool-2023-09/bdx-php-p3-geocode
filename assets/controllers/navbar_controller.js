import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets =  ['list'];
    openActive(e)
    {
        console.log('click');
        this.listTargets.forEach((item) => item.classList.remove("active"))
        e.currentTarget.closest('li').classList.add("active");
    }

    connect()
    {
      console.log('navbar connect')
    }
}
