
window.onload = function () {
    let index = -1;
    let timer;

    show();
    timer = setInterval(show, 3000);

    function show(a) {
        let banner_list = document.querySelectorAll('.banner-list li');

        if (a) {
            index--;
            index = index < 0 ? banner_list.length - 1 : index;
        } else {
            index++;
            index = index > banner_list.length - 1 ? 0 : index;
        }

        banner_list.forEach(function (v) {
            v.className = '';
        });
        banner_list[index].className = 'show';

    }

    document.getElementById('prev').onclick = function () {
        clearInterval(timer);
        show(1);
        timer = setInterval(show, 3000);
    }

    document.getElementById('next').onclick = function () {
        clearInterval(timer);
        show();
        timer = setInterval(show, 3000);
    }
}
