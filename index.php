<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengaduan Aspirasi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">

<div class="bg-white w-full max-w-sm p-8 rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-50">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-800">Login Portal</h2>
        <p class="text-slate-500 text-sm mt-1">Sistem Pengaduan Aspirasi Siswa</p>
    </div>

    <form action="proses_login.php" method="POST" id="loginForm" class="space-y-4">
        <input type="hidden" name="login" value="true">
        <input type="hidden" name="role" id="role-input" value="">

        <div>
            <label for="identifier" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Identitas</label>
            <input type="text" name="identifier" id="identifier" 
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-700"
                   placeholder="Username atau NIS" required>
            <p class="text-red-500 text-xs font-bold mt-2 hidden" id="error-msg">
                <span class="inline-block mr-1">⚠️</span> Pengguna tidak ditemukan!
            </p>
        </div>

        <div id="password-container" class="hidden animate-in fade-in slide-in-from-top-2 duration-300">
            <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Password</label>
            <input type="password" name="password" id="password" 
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-700"
                   placeholder="••••••••">
            <p class="text-[10px] text-blue-600 mt-2 font-medium italic">* Silakan masukkan password administrator</p>
        </div>

        <button type="submit" id="btn-login" 
                class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98]">
            Lanjut
        </button>

        <div class="text-center pt-2">
            <p class="text-xs text-slate-400">Pastikan data yang Anda masukkan valid.</p>
        </div>
    </form>
</div>

<script>
    // 1. Deklarasi Elemen DOM
    const elements = {
        form: document.getElementById("loginForm"),
        role: document.getElementById("role-input"),
        id: document.getElementById("identifier"),
        error: document.getElementById("error-msg"),
        passCont: document.getElementById("password-container"),
        pass: document.getElementById("password"),
        btn: document.getElementById("btn-login")
    };

    let loginStep = 1; 

    // 2. Helper untuk mengubah status tombol
    const setButton = (text, disabled) => {
        elements.btn.innerText = text;
        elements.btn.disabled = disabled;
        elements.btn.className = disabled 
            ? "w-full py-3.5 bg-slate-300 text-slate-500 font-bold rounded-xl cursor-not-allowed transition-all" 
            : "w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98]";
    };

    // 3. Event Listener Klik Tombol
    elements.btn.addEventListener("click", (e) => {
        if (loginStep === 1) {
            e.preventDefault(); 
            const identifierVal = elements.id.value.trim();

            if (!identifierVal) return alert("Silakan isi Username atau NIS terlebih dahulu.");
            
            setButton("Mengecek...", true);
            cekPengguna(identifierVal);
        }
    });

    // 4. Fungsi Utama Fetch Data
    function cekPengguna(identifier) {
        fetch("proses_login.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "action=check_user&identifier=" + encodeURIComponent(identifier)
        })
        .then(res => res.ok ? res.json() : Promise.reject("HTTP Error " + res.status))
        .then(data => {
            if (data.status === "ada") {
                elements.error.classList.add("hidden");
                elements.role.value = data.role; 
                elements.id.readOnly = true;
                elements.id.classList.add("bg-slate-200", "text-slate-500", "cursor-not-allowed");

                if (data.role === "admin") {
                    elements.passCont.classList.remove("hidden");
                    elements.pass.focus();
                    setButton("Masuk", false);
                    loginStep = 2; 
                } else {
                    elements.form.submit(); 
                }
            } else {
                elements.error.classList.remove("hidden");
                setButton("Lanjut", false);
            }
        })
        .catch(err => {
            console.error("Terjadi kesalahan:", err);
            alert("Terjadi kesalahan saat memeriksa pengguna.");
            setButton("Lanjut", false);
        });
    }
</script>

</body>
</html>