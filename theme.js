(function(root) {
	if (localStorage.getItem('is-dark')) {
		root.classList.add('dark');
	}
})(document.documentElement);