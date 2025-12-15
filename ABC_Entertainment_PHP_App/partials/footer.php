</main>
<footer class="footer">
  <div class="container footer__inner">
    <div>ABC Entertainment Company • CPSC 332</div>
    <div class="muted">Built with PHP + MySQL (PDO). Demo UI/UX for class project.</div>
  </div>
</footer>
<script>
// Tiny enhancement: client-side table filtering
document.querySelectorAll('[data-table-filter]').forEach(input => {
  const tableId = input.getAttribute('data-table-filter');
  const table = document.getElementById(tableId);
  if (!table) return;
  input.addEventListener('input', () => {
    const q = input.value.toLowerCase();
    table.querySelectorAll('tbody tr').forEach(tr => {
      tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });
});
</script>
</body>
</html>
