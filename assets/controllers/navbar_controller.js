import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets =  ['list'];

    connect()
    {
        const activeElement = document.getElementsByClassName('active')[0];
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
        this.listTargets.forEach((item) => item.classList.remove("active"));
        e.currentTarget.closest('li').classList.add("active");
        const activeElement = document.getElementsByClassName('active')[0];
        if (document.getElementById('indicator-admin') != null) {
            if (activeElement.parentElement.classList.contains('admin')) {
                document.getElementById('indicator-admin').style.display = 'block';
                document.getElementById('indicator-user').style.display = 'none';
            } else {
                document.getElementById('indicator-admin').style.display = 'none';
                document.getElementById('indicator-user').style.display = 'block';
            }
        }
        if (e.currentTarget.closest('li').id === 'navbar-search') {
            const trigger = new CustomEvent('trigger-search');
            window.dispatchEvent(trigger);
        }
    }
}
