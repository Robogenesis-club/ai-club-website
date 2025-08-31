<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
    <div id="caption"></div>
</div>
<script>
    function openModal(src) {
        document.getElementById('imageModal').style.display = 'block';
        document.getElementById('modalImage').src = src;
    }
    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target !== document.getElementById('modalImage')) {
            closeModal();
        }
    });
    window.addEventListener('load', () => AOS.init({ duration: 900, once: true }));
</script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js" defer></script>
<script src="js/main.js" defer></script>
</body>
</html>