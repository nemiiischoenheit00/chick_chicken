
(function () {
  const labels  = ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent!'];
  let selected  = 0;
  const stars   = document.querySelectorAll('.rev-star');
  const lbl     = document.getElementById('rev-star-label');

  function highlight(val) {
    stars.forEach(s => s.style.color = +s.dataset.val <= val ? '#F5A623' : '#ddd');
  }

  stars.forEach(s => {
    s.addEventListener('mouseenter', () => highlight(+s.dataset.val));
    s.addEventListener('mouseleave', () => highlight(selected));
    s.addEventListener('click', () => {
      selected = +s.dataset.val;
      highlight(selected);
      lbl.textContent = labels[selected];
    });
  });

  document.getElementById('rev-submit-btn').addEventListener('click', async () => {
    const name   = document.getElementById('reviewerName').value.trim();
    const review = document.getElementById('reviewText').value.trim();

    if (!name || !selected || !review) {
      alert('Please fill in all fields and select a rating.');
      return;
    }

    try {
      const res  = await fetch('submit-review.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ name, rating: selected, review })
      });
      const data = await res.json();

      if (data.success) {
        document.getElementById('review-form-body').style.display = 'none';
        document.querySelector('.modal-footer').style.display      = 'none';
        document.getElementById('review-success-body').style.display = 'block';
      } else {
        alert('Something went wrong: ' + data.error);
      }
    } catch (err) {
      alert('Network error. Please try again.');
    }
  });

  // reset form when modal is closed
  document.getElementById('reviewModal').addEventListener('hidden.bs.modal', () => {
    selected = 0;
    highlight(0);
    lbl.textContent = 'Tap a star to rate';
    document.getElementById('reviewerName').value = '';
    document.getElementById('reviewText').value   = '';
    document.getElementById('review-form-body').style.display      = 'block';
    document.querySelector('.modal-footer').style.display           = '';
    document.getElementById('review-success-body').style.display   = 'none';
  });
})();
