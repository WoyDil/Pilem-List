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
