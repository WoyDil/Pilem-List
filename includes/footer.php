</main>
<footer>© <?= date('Y') ?> PILEM LIST</footer>
<script>
(function () {
  var b = document.body, bb = document.getElementById('bb');
  function set(o) { b.classList.toggle('open', o); bb.setAttribute('aria-expanded', o); }
  bb.onclick = function () { set(!b.classList.contains('open')); };
  document.getElementById('scrim').onclick = function () { set(false); };
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') set(false); });
})();
</script>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const profileBtn = document.getElementById('profileBtn');
  const profileDropdown = document.getElementById('profileDropdown');

  if (profileBtn && profileDropdown) {
    // Buka/tutup saat tombol profil diklik
    profileBtn.addEventListener('click', function(e) {
      e.stopPropagation(); // Mencegah event klik menyebar
      profileDropdown.classList.toggle('show');
      profileBtn.classList.toggle('active');
    });

    // Tutup dropdown kalau klik di luar area dropdown
    window.addEventListener('click', function(e) {
      if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
        profileBtn.classList.remove('active');
      }
    });
  }
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const themeText = document.getElementById('themeText');
  const body = document.body;
  const currentTheme = localStorage.getItem('theme');
  if (currentTheme === 'light') {
    body.classList.add('light-mode');
    if(themeIcon) themeIcon.innerText = '☀️';
    if(themeText) themeText.innerText = 'Mode Terang';
  }

  if (themeToggle) {
    themeToggle.addEventListener('click', function(e) {
      e.preventDefault(); // Biar link nggak loncat ke atas
      
      body.classList.toggle('light-mode');
      
      if (body.classList.contains('light-mode')) {
        // Ubah ke terang
        localStorage.setItem('theme', 'light');
        themeIcon.innerText = '☀️';
        themeText.innerText = 'Mode Terang';
      } else {
        // Ubah ke gelap
        localStorage.setItem('theme', 'dark');
        themeIcon.innerText = '🌙';
        themeText.innerText = 'Mode Gelap';
      }
    });
  }

});
</script>
