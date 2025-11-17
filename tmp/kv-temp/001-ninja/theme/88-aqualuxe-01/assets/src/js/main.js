/* Progressive enhancement + contact form via REST */
(function(){
	// Replace no-js with js
	document.documentElement.classList.remove('no-js');
	// Contact form handler
	var form = document.getElementById('aqualuxe-contact');
	if (!form) return;
	form.addEventListener('submit', function(e){
		if (!window.fetch || !window.AQUALUXE) return; // let fallback submit
		e.preventDefault();
		var fd = new FormData(form);
		var data = Object.fromEntries(fd.entries());
		var feedback = form.querySelector('.form-feedback');
		feedback.textContent = '';
		fetch(AQUALUXE.restRoot + '/contact', {
			method: 'POST',
			headers: { 'X-WP-Nonce': AQUALUXE.nonce },
			body: new URLSearchParams(data)
		}).then(function(res){
			if(!res.ok) return res.json().then(function(j){ throw new Error(j.message || 'Error'); });
			return res.json();
		}).then(function(){
			feedback.textContent = 'Thanks! Your message was sent.';
			form.reset();
		}).catch(function(err){
			feedback.textContent = err.message || 'Something went wrong.';
		});
	});
})();
