(function(root) {
	let toggle = root.querySelector('a');
	toggle.addEventListener('click', e => {
		root.classList.toggle('dark');
		if (root.classList.contains('dark')) {
			localStorage.setItem('is-dark', 1);
		} else {
			localStorage.removeItem('is-dark');
		}
		e.preventDefault();
	}, false);
})(document.documentElement);