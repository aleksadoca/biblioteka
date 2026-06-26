    </main>

    <!-- Podnožje -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-white mb-3"><i class="bi bi-book"></i> Biblioteka</h5>
                    <p class="text-light opacity-75">Moderan sistem za upravljanje bibliotekom. Čuvajmo znanje zajedno.</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white mb-3">Linkovi</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/index.php" class="text-light opacity-75"><i class="bi bi-chevron-right"></i> Početna</a></li>
                        <li class="mb-2"><a href="/index.php?page=books" class="text-light opacity-75"><i class="bi bi-chevron-right"></i> Knjige</a></li>
                        <li class="mb-2"><a href="/index.php?page=login" class="text-light opacity-75"><i class="bi bi-chevron-right"></i> Prijava</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white mb-3">Dokumentacija</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2 text-light opacity-75"><i class="bi bi-file-earmark-text me-2"></i> README.md</li>
                        <li class="mb-2 text-light opacity-75"><i class="bi bi-diagram-3 me-2"></i> ER dijagram</li>
                        <li class="mb-2 text-light opacity-75"><i class="bi bi-database me-2"></i> SQL bekap</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center">
                <p class="text-light opacity-50 mb-0">&copy; <?= date('Y') ?> Biblioteka. Sva prava zadržana.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Prilagođeni JavaScript -->
    <script src="/js/app.js"></script>
</body>
</html>
