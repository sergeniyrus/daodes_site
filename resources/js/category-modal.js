
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('open-category-modal').addEventListener('click', function () {
        document.getElementById('category-modal').style.display = 'block';
    });

    document.querySelector('.close').addEventListener('click', function () {
        document.getElementById('category-modal').style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        const modal = document.getElementById('category-modal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
