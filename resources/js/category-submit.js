
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('category-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        submitBtn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) return response.json().then(err => { throw err; });
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const select = document.getElementById('category-select');
                const option = document.createElement('option');
                option.value = data.category.id;
                option.textContent = `${data.category.name_ru} / ${data.category.name_en}`;
                select.appendChild(option);
                select.value = data.category.id;
                document.getElementById('category-modal').style.display = 'none';
                form.reset();
                alert(data.message);
            }
        })
        .catch(error => {
            const errors = error.errors || [error.message || 'Unknown error'];
            alert(errors.join('\n'));
        })
        .finally(() => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    });
});
