import { Controller } from '@hotwired/stimulus';
import modalForm_controller from "./modal-form_controller";

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

    connect()
    {
        console.log('navbar connected');
        const activeElement = document.getElementsByClassName('active')[0];
        console.log(activeElement);
        if (document.getElementById('indicator-admin') != null) {
            if (activeElement.parentElement.classList.contains('admin')) {
                console.log('plop');
                document.getElementById('indicator-admin').style.display = 'block';
                document.getElementById('indicator-user').style.display = 'none';
            } else {
                console.log('blam');

                document.getElementById('indicator-admin').style.display = 'none';
                document.getElementById('indicator-user').style.display = 'block';
            }
        }
    }
    openActive(e)
    {
        console.log('click');

        this.listTargets.forEach((item) => item.classList.remove("active"));
        e.currentTarget.closest('li').classList.add("active");
        const activeElement = document.getElementsByClassName('active')[0];
        console.log(activeElement);
        if (document.getElementById('indicator-admin') != null) {
            if (activeElement.parentElement.classList.contains('admin')) {
                console.log('plop');
                document.getElementById('indicator-admin').style.display = 'block';
                document.getElementById('indicator-user').style.display = 'none';
            } else {
                console.log('blam');

                document.getElementById('indicator-admin').style.display = 'none';
                document.getElementById('indicator-user').style.display = 'block';
            }
        }
        if (e.currentTarget.closest('li').id === 'navbar-search') {
            console.log('search');
            const trigger = new CustomEvent('trigger-search');
            window.dispatchEvent(trigger);
        }
    }


}
