```html:resources/views/index.blade.php
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Monitoring Kehadiran Guru & Siswa - SMAN 2 Cikarang Utara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="module">
        // Import Firebase SDK modules
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.9.0/firebase-app.js";
        import { getFirestore, collection, doc, setDoc, deleteDoc, getDocs, onSnapshot, writeBatch, query, where } from "https://www.gstatic.com/firebasejs/10.9.0/firebase-firestore.js";
        
        // Firebase Configuration
        const firebaseConfig = {
            apiKey: "AlzaSyDcZG3CdfLV_ezXRL0tUKorSAxVyihMR1g",
            authDomain: "absensi-smanda-30054.firebaseapp.com",
            projectId: "absensi-smanda-30054",
            storageBucket: "absensi-smanda-30054.firebasestorage.app",
            messagingSenderId: "851155194211",
            appId: "1:851155194211:web:d097ce0f24f6d9d83d4356",
            measurementId: "G-64M0QQTW1G"
        };
        
        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);
        
        // Ekspor ke window agar bisa diakses oleh AlpineJS
        window.db = db;
        window.fb = { collection, doc, setDoc, deleteDoc, getDocs, onSnapshot, writeBatch, query, where };
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            background: linear-gradient(45deg, #ff9a9e, #fecfef, #a1c4fd, #c2e9fb, #fbc2eb);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            overflow-x: hidden;
            color: #1f2937;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.2); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.4); }
        .print-only { display: none; }
        
        @media print {
            html, body { background: white !important; height: auto !important; overflow: visible !important; }
            .h-screen { height: auto !important; min-height: auto !important; }
            .overflow-hidden, .overflow-y-auto { overflow: visible !important; }
            aside, header, #animation-container, .no-print, .toast-container { display: none !important; }
            main { background: transparent !important; padding: 0 !important; margin: 0 !important; height: auto !important; overflow: visible !important; }
            .print-only { display: block !important; }
            .shadow-xl, .shadow-md, .shadow-lg, .shadow-sm { box-shadow: none !important; }
            .bg-white\/90, .bg-white\/80, .glass-panel { background: transparent !important; border: none !important; backdrop-filter: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
        .login-card-bg {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1), inset 0 0 20px rgba(255,255,255,0.5);
        }
        @keyframes slideBounce {
            0%, 100% { transform: translateX(-15px); }
            50% { transform: translateX(15px); }
        }
        .animated-title-login {
            display: inline-block;
            animation: slideBounce 3s ease-in-out infinite, rainbowText 2s linear infinite;
            text-shadow: 2px 2px 0 #fff, -2px 2px 0 #fff, 2px 2px 0 #fff, -2px 2px 0 #fff, 0px 5px 15px rgba(0,0,0,0.3);
        }
        .animated-subtitle-login {
            display: inline-block;
            animation: slideBounce 4s ease-in-out infinite alternate-reverse, rainbowText 3.5s linear infinite reverse;
            text-shadow: 1px 1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff, -1px 1px 0 #fff, 0px 3px 8px rgba(0,0,0,0.3);
            font-weight: 900;
        }
        @keyframes zoomInOut {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .animated-logo {
            animation: zoomInOut 2s ease-in-out infinite;
            object-fit: contain;
        }
        .status-radio input:checked + div {
            transform: scale(1.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px 1px rgba(0, 0, 0, 0.06);
            border-width: 2px;
        }
        .status-radio.hadir input:checked + div { background-color: #22c55e; color: white; border-color: #16a34a; }
        .status-radio.terlambat input:checked + div { background-color: #eab308; color: white; border-color: #ca8a04; }
        .status-radio.izin input:checked + div { background-color: #3b82f6; color: white; border-color: #2563eb; }
        .status-radio.sakit input:checked + div { background-color: #f97316; color: white; border-color: #ea580c; }
        .status-radio.alpa input:checked + div { background-color: #ef4444; color: white; border-color: #dc2626; }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('appData', () => ({
                isLoggedIn: false,
                isSidebarOpen: false,
                isLoading: false,
                isProcessingData: false,
                loginForm: { username: "", password: "" },
                currentUser: { id: null, username: "", role: "", nama: "", kelas_km: "" },
                currentTab: 'dashboard',
                waktuSekarang: "",
                currentTimeTrigger: Date.now(),
                toasts: [],
                dbStatus: 'connecting',
                dbInfo: { name: "", url: "", id: "", message: "", lastSync: "" },
                isSyncing: false,
                uploadedSchedules: [],
                masterGuru: [],
                masterKelas: [],
                masterMapel: [],
                masterSiswa: [],
                attendances: [],
                studentAttendances: [],
                teacherStudentAttendances: [],
                showModal: false,
                modalMode: 'tambah',
                editId: null,
                formData: {},
                showDeleteModal: false,
                itemToDelete: null,
                showDeleteAllModal: false,
                showTransferModal: false,
                transferSource: "",
                transferTarget: "",
                showPasswordModal: false,
                passwordForm: { oldPassword: "", newPassword: "", confirmPassword: "" },
                showTugasModal: false,
                tugasForm: { jadwalIds: [], deskripsi: "" },
                showTugasDetailModal: false,
                tugasDetailData: null,
                selectedDayGuru: 'Senin',
                sortMonitoringColumn: 'guru',
                sortMonitoringDirection: 'asc',
                searchMonitoringGuru: "",
                searchMonitoringKelas: "",
                reportStartDate: "",
                reportEndDate: "",
                selectedTanggalKM: "",
                draftAbsensiKM: [],
                reportAdminSiswaStartDate: "",
                reportAdminSiswaEndDate: "",
                filterKelasLaporanSiswa: 'Semua',
                chartInstance: null,
                selectedTanggalGuru: "",
                selectedKelasGuru: "",
                draftAbsensiGuru: [],
                reportSiswaGuruStartDate: "",
                reportSiswaGuruEndDate: "",
                reportSiswaGuruKelas: 'Semua',
                holidays: [],
                liburForm: { tanggal: "", keterangan: "" },
                koreksiGuruForm: { tanggal: "", guru: "" },
                masterUser: [],
                unsubscribeList: [],

                generateUniqueId(prefix = 'id') {
                    return prefix + '_' + Date.now() + '_' + Math.random().toString(36).substring(2, 8);
                },
                saveToLocal(key, data) {
                    try { localStorage.setItem('SMANDA_V2_' + key, JSON.stringify(data)); } catch(e) {}
                },
                loadFromLocal(key, defaultData) {
                    const localData = localStorage.getItem('SMANDA_V2_' + key);
                    return localData ? JSON.parse(localData) : defaultData;
                },
                async firestoreBatchSave(colName, records) {
                    if (!records || records.length === 0) return;
                    const chunkSize = 400;
                    for (let i = 0; i < records.length; i += chunkSize) {
                        const chunk = records.slice(i, i + chunkSize);
                        const batch = window.fb.writeBatch(window.db);
                        chunk.forEach(rec => {
                            const docRef = window.fb.doc(window.db, colName, String(rec.id));
                            batch.set(docRef, rec);
                        });
                        await batch.commit();
                    }
                },
                async firestoreSaveDbTable(colName, records) {
                    const snap = await window.fb.getDocs(window.fb.collection(window.db, colName));
                    const docsToDelete = snap.docs;
                    const chunkSize = 400;
                    for (let i = 0; i < docsToDelete.length; i += chunkSize) {
                        const chunk = docsToDelete.slice(i, i + chunkSize);
                        const batch = window.fb.writeBatch(window.db);
                        chunk.forEach(d => batch.delete(d.ref));
                        await batch.commit();
                    }
                    await this.firestoreBatchSave(colName, records);
                },
                async init() {
                    this.reportStartDate = this.getTodayDateString();
                    this.reportEndDate = this.getTodayDateString();
                    this.selectedTanggalKM = this.getTodayDateString();
                    this.reportAdminSiswaStartDate = this.getTodayDateString();
                    this.reportAdminSiswaEndDate = this.getTodayDateString();
                    this.selectedTanggalGuru = this.getTodayDateString();
                    this.reportSiswaGuruStartDate = this.getTodayDateString();
                    this.reportSiswaGuruEndDate = this.getTodayDateString();
                    this.koreksiGuruForm.tanggal = this.getTodayDateString();
                    this.updateTime();
                    setInterval(() => {
                        this.updateTime();
                    }, 60000);

                    this.masterUser = this.loadFromLocal('USERS', []);
                    this.masterGuru = this.loadFromLocal('TEACHERS', []);
                    this.masterKelas = this.loadFromLocal('CLASSES', []);
                    this.masterMapel = this.loadFromLocal('SUBJECTS', []);
                    this.masterSiswa = this.loadFromLocal('STUDENTS', []);
                    this.uploadedSchedules = this.loadFromLocal('SCHEDULES', []);
                    this.attendances = this.loadFromLocal('ATTENDANCES', []);
                    this.studentAttendances = this.loadFromLocal('STUDENT_ATTENDANCES', []);
                    this.teacherStudentAttendances = this.loadFromLocal('TEACHER_STUDENT_ATTENDANCES', []);
                    this.holidays = this.loadFromLocal('HOLIDAYS', []);
                    
                    this.syncWithDatabase();
                    
                    this.$watch('currentTab', (val) => {
                        if (val === 'laporan_siswa') { setTimeout(() => this.renderAdminChart(), 300); }
                        if (val === 'km_dashboard') this.loadKmAttendanceData();
                        if (val === 'guru_absensi_siswa') this.loadGuruAttendanceData();
                    });
                    this.$watch('filterKelasLaporanSiswa', () => { if (this.currentTab === 'laporan_siswa') this.renderAdminChart(); });
                    this.$watch('selectedTanggalGuru', () => { if (this.currentTab === 'guru_absensi_siswa') this.loadGuruAttendanceData(); });
                    this.$watch('selectedKelasGuru', () => { if (this.currentTab === 'guru_absensi_siswa') this.loadGuruAttendanceData(); });
                },
                async syncWithDatabase(forceRefresh = false) {
                    this.isSyncing = true;
                    this.dbStatus = 'connecting';
                    try {
                        if (typeof window.db === 'undefined' || typeof window.fb === 'undefined') {
                            setTimeout(() => this.syncWithDatabase(forceRefresh), 500);
                            return;
                        }
                        const today = this.getTodayDateString();
                        this.unsubscribeList.forEach(unsub => unsub());
                        this.unsubscribeList = [];
                        let loadedCount = 0;
                        const totalColToLoad = 10;
                        
                        const checkAllLoaded = () => {
                            loadedCount++;
                            if (loadedCount === totalColToLoad) {
                                this.dbStatus = 'connected';
                                this.dbInfo = {
                                    name: 'Cloud Firestore Real-Time',
                                    url: '#',
                                    id: 'absensi-smanda-30054',
                                    message: 'Sinkronisasi Aktif',
                                    lastSync: new Date().toLocaleTimeString('id-ID')
                                };
                                this.isSyncing = false;
                                this.checkAutoAlpaSchedules();
                                
                                if(this.masterUser.length === 0) {
                                    const adminUser = { id: 'usr_1', nama: 'Administrator SMAN 2', username: 'admin', password: '123', role: 'admin', status: 'AKTIF', showPassword: false };
                                    window.fb.setDoc(window.fb.doc(window.db, 'USERS', 'usr_1'), adminUser);
                                    this.masterUser.push(adminUser);
                                }
                                
                                if (this.currentTab === 'km_dashboard') this.loadKmAttendanceData();
                                if (this.currentTab === 'guru_absensi_siswa') this.loadGuruAttendanceData();
                                
                                if (forceRefresh && this.isLoggedIn) {
                                    this.showToast('Data Diperbarui', 'Penyegaran data selesai.', 'success');
                                }
                            }
                        };
                        
                        const handleError = (err) => {
                            this.dbStatus = 'error';
                            this.dbInfo.message = err.message || err.toString();
                            this.isSyncing = false;
                            console.warn('Realtime error:', err);
                        };

                        const collections = [
                            { name: 'USERS', prop: 'masterUser' },
                            { name: 'TEACHERS', prop: 'masterGuru' },
                            { name: 'CLASSES', prop: 'masterKelas' },
                            { name: 'SUBJECTS', prop: 'masterMapel' },
                            { name: 'STUDENTS', prop: 'masterSiswa' },
                            { name: 'SCHEDULES', prop: 'uploadedSchedules' },
                            { name: 'ATTENDANCES', prop: 'attendances' },
                            { name: 'HOLIDAYS', prop: 'holidays' }
                        ];

                        collections.forEach(col => {
                            if (!forceRefresh && this[col.prop] && this[col.prop].length > 0) {
                                checkAllLoaded();
                            } else {
                                window.fb.getDocs(window.fb.collection(window.db, col.name)).then(snap => {
                                    this[col.prop] = snap.docs.map(doc => doc.data());
                                    this.saveToLocal(col.name, this[col.prop]);
                                    checkAllLoaded();
                                }).catch(handleError);
                            }
                        });

                        const unsubAtt = window.fb.onSnapshot(window.fb.collection(window.db, 'ATTENDANCES'), snap => {
                            this.attendances = snap.docs.map(doc => doc.data());
                            this.saveToLocal('ATTENDANCES', this.attendances);
                            if (this.currentTab === 'dashboard') this.currentTimeTrigger = Date.now();
                            if (loadedCount < totalColToLoad) checkAllLoaded();
                        }, handleError);
                        this.unsubscribeList.push(unsubAtt);

                        const qStudentAtt = window.fb.query(window.fb.collection(window.db, 'STUDENT_ATTENDANCES'), window.fb.where('tanggal', '==', today));
                        const unsubStudentAtt = window.fb.onSnapshot(qStudentAtt, snap => {
                            const todayData = snap.docs.map(d => d.data());
                            const otherDates = this.studentAttendances.filter(a => a.tanggal !== today);
                            this.studentAttendances = [...otherDates, ...todayData];
                            this.saveToLocal('STUDENT_ATTENDANCES', this.studentAttendances);
                            if (this.currentTab === 'km_dashboard') this.loadKmAttendanceData();
                            if (loadedCount < totalColToLoad) checkAllLoaded();
                        }, handleError);
                        this.unsubscribeList.push(unsubStudentAtt);

                        const qTeacherAtt = window.fb.query(window.fb.collection(window.db, 'TEACHER_STUDENT_ATTENDANCES'), window.fb.where('tanggal', '==', today));
                        const unsubTeacherAtt = window.fb.onSnapshot(qTeacherAtt, snap => {
                            const todayData = snap.docs.map(d => d.data());
                            const otherDates = this.teacherStudentAttendances.filter(a => a.tanggal !== today);
                            this.teacherStudentAttendances = [...otherDates, ...todayData];
                            this.saveToLocal('TEACHER_STUDENT_ATTENDANCES', this.teacherStudentAttendances);
                            if (this.currentTab === 'guru_absensi_siswa') this.loadGuruAttendanceData();
                            if (loadedCount < totalColToLoad) checkAllLoaded();
                        }, handleError);
                        this.unsubscribeList.push(unsubTeacherAtt);

                    } catch (e) {
                        this.dbStatus = 'error';
                        this.dbInfo.message = e.message || e.toString();
                        this.isSyncing = false;
                    }
                },
                async fetchAdminLaporanSiswa() {
                    this.isLoading = true;
                    this.showToast('Memuat...', 'Mengambil data history absensi dari server...', 'info');
                    try {
                        const colRef = window.fb.collection(window.db, 'STUDENT_ATTENDANCES');
                        let q = window.fb.query(
                            colRef,
                            window.fb.where('tanggal', '>=', this.reportAdminSiswaStartDate),
                            window.fb.where('tanggal', '<=', this.reportAdminSiswaEndDate)
                        );
                        const snap = await window.fb.getDocs(q);
                        let fetched = snap.docs.map(d => d.data());
                        if(this.filterKelasLaporanSiswa !== 'Semua') {
                            fetched = fetched.filter(a => (a.kelas || "").trim() === this.filterKelasLaporanSiswa.trim());
                        }
                        const otherDates = this.studentAttendances.filter(a =>
                            a.tanggal < this.reportAdminSiswaStartDate || a.tanggal > this.reportAdminSiswaEndDate
                        );
                        this.studentAttendances = [...otherDates, ...fetched];
                        this.renderAdminChart();
                        this.showToast('Sukses', `${fetched.length} riwayat absensi berhasil dimuat.`, 'success');
                    } catch(e) {
                        this.showToast('Error', 'Terjadi kesalahan saat memuat riwayat dari Firestore.', 'error');
                    } finally {
                        this.isLoading = false;
                    }
                },
                showDbErrorDetail() {
                    const msg = this.dbInfo.message || 'Pemeriksaan API key atau Security Rules diperlukan.';
                    this.showToast('Info Database', msg, 'warning');
                },
                async login() {
                    this.isLoading = true;
                    const inputUsername = String(this.loginForm.username).trim().toLowerCase();
                    let validUser = this.masterUser.find(u =>
                        String(u.username).trim().toLowerCase() === inputUsername &&
                        String(u.password).trim() === String(this.loginForm.password).trim()
                    );
                    if (!validUser) {
                        await this.syncWithDatabase(true);
                        validUser = this.masterUser.find(u =>
                            String(u.username).trim().toLowerCase() === inputUsername &&
                            String(u.password).trim() === String(this.loginForm.password).trim()
                        );
                    }
                    this.isLoading = false;
                    
                    if(validUser) {
                        if(validUser.status !== 'AKTIF') {
                            this.showToast('Ditolak', 'Akun Anda sedang dinonaktifkan.', 'error');
                            return;
                        }
                        this.isLoggedIn = true;
                        this.currentUser = {
                            id: validUser.id,
                            username: validUser.username,
                            role: validUser.role,
                            nama: validUser.nama,
                            kelas_km: (validUser.kelas_km || "").trim()
                        };
                        
                        if (validUser.role === 'kmkelas') {
                            this.currentTab = 'km_dashboard';
                            this.loadKmAttendanceData();
                        } else {
                            this.currentTab = 'dashboard';
                        }
                        
                        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        let hariIni = days[new Date().getDay()];
                        if(hariIni === 'Minggu' || hariIni === 'Sabtu') hariIni = 'Senin';
                        this.selectedDayGuru = hariIni;
                        
                        this.showToast('Login Berhasil', `Selamat datang, ${validUser.nama}.`, 'success');
                    } else {
                        this.showToast('Gagal', 'Username atau Password salah.', 'error');
                    }
                },
                updateTime() {
                    const now = new Date();
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' };
                    this.waktuSekarang = now.toLocaleDateString('id-ID', options) + ' WIB';
                    this.currentTimeTrigger = now.getTime();
                },
                getTodayDateString() {
                    const d = new Date();
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                },
                parseTime(timeStr) {
                    if(!timeStr) return null;
                    let t = timeStr.replace(/\./g, ':').replace(/[^0-9:]/g, "");
                    let parts = t.split(':');
                    if(parts.length >= 2) {
                        return parseInt(parts[0]) * 60 + parseInt(parts[1]);
                    }
                    return null;
                },
                isTimeOngoing(timeString) {
                    const trigger = this.currentTimeTrigger;
                    if (!timeString || !timeString.includes('-')) return false;
                    try {
                        const [startStr, endStr] = timeString.split('-');
                        const startTotal = this.parseTime(startStr);
                        const endTotal = this.parseTime(endStr);
                        const now = new Date(trigger);
                        const currentTotal = now.getHours() * 60 + now.getMinutes();
                        if (startTotal !== null && endTotal !== null) {
                            return currentTotal >= startTotal && currentTotal <= endTotal;
                        }
                        return false;
                    } catch(e) { return false; }
                },
                isTimePastOrOngoing(timeString) {
                    const trigger = this.currentTimeTrigger;
                    if (!timeString || !timeString.includes('-')) return true;
                    try {
                        const [startStr] = timeString.split('-');
                        const startTotal = this.parseTime(startStr);
                        const now = new Date(trigger);
                        const currentTotal = now.getHours() * 60 + now.getMinutes();
                        if (startTotal !== null) {
                            return currentTotal >= startTotal;
                        }
                        return true;
                    } catch(e) { return true; }
                },
                isScheduleTimePassed(timeString) {
                    const trigger = this.currentTimeTrigger;
                    const now = new Date(trigger);
                    const currentTotal = now.getHours() * 60 + now.getMinutes();
                    return currentTotal >= 960; // 16:00
                },
                async checkAutoAlpaSchedules() {
                    const today = this.getTodayDateString();
                    const isHoliday = this.holidays.some(h => h.tanggal === today);
                    if (isHoliday) return;
                    
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const currentDayName = days[new Date().getDay()];
                    let recordsToSave = [];
                    
                    this.uploadedSchedules.forEach(s => {
                        if ((s.hari || "").trim() === currentDayName) {
                            if (this.isScheduleTimePassed(s.waktu)) {
                                let existingIdx = this.attendances.findIndex(a =>
                                    (String(a.jadwalId) === String(s.id) && a.tanggal === today) ||
                                    (a.tanggal === today && (a.guru || "").trim().toLowerCase() === (s.guru || "").trim().toLowerCase() && (a.kelas || "").trim().toLowerCase() === (s.kelas || "").trim().toLowerCase())
                                );
                                if (existingIdx === -1) {
                                    const record = {
                                        id: this.generateUniqueId('att'),
                                        jadwalId: s.id,
                                        tanggal: today,
                                        hari: s.hari,
                                        guru: s.guru,
                                        kelas: s.kelas,
                                        mapel: s.mapel,
                                        jamke: s.jamke,
                                        status: 'ALPA',
                                        deskripsiTugas: '-'
                                    };
                                    this.attendances.push(record);
                                    recordsToSave.push(record);
                                }
                            }
                        }
                    });
                    
                    if (recordsToSave.length > 0) {
                        this.attendances = [...this.attendances];
                        this.saveToLocal('ATTENDANCES', this.attendances);
                        try {
                            await this.firestoreBatchSave('ATTENDANCES', recordsToSave);
                        } catch(e) {}
                    }
                },
                logout() {
                    this.isLoggedIn = false;
                    this.loginForm.password = "";
                    this.showToast('Logout', 'Sesi telah diakhiri.', 'info');
                },
                async ubahPassword() {
                    if (this.passwordForm.newPassword !== this.passwordForm.confirmPassword) {
                        this.showToast('Gagal', 'Password baru dan konfirmasi tidak cocok.', 'error');
                        return;
                    }
                    if (this.passwordForm.newPassword.length < 3) {
                        this.showToast('Gagal', 'Password baru minimal 3 karakter.', 'error');
                        return;
                    }
                    let userIndex = this.masterUser.findIndex(u =>
                        String(u.username).trim().toLowerCase() === String(this.currentUser.username).trim().toLowerCase()
                    );
                    if (userIndex === -1) return;
                    
                    if (this.masterUser[userIndex].password !== this.passwordForm.oldPassword) {
                        this.showToast('Gagal', 'Password lama tidak cocok.', 'error');
                        return;
                    }
                    
                    this.masterUser[userIndex].password = this.passwordForm.newPassword;
                    this.saveToLocal('USERS', this.masterUser);
                    try {
                        const payload = JSON.parse(JSON.stringify(this.masterUser[userIndex]));
                        await window.fb.setDoc(window.fb.doc(window.db, 'USERS', payload.id), payload);
                        this.showToast('Sukses', 'Password berhasil diubah di database.', 'success');
                        this.showPasswordModal = false;
                        this.passwordForm = { oldPassword: "", newPassword: "", confirmPassword: "" };
                    } catch (e) {
                        this.showToast('Peringatan', 'Password tersimpan lokal, koneksi database terganggu.', 'warning');
                        this.showPasswordModal = false;
                    }
                },
                getTabTitle() {
                    const titles = {
                        dashboard: 'Dashboard Realtime',
                        monitoring_guru: 'Monitoring Guru Detail',
                        laporan: 'Laporan Kehadiran Guru',
                        master_guru: 'Master Data Guru',
                        master_kelas: 'Master Data Kelas',
                        master_mapel: 'Master Mata Pelajaran',
                        master_siswa: 'Master Data Siswa',
                        jadwal: 'Jadwal Pelajaran',
                        user: 'Manajemen Pengguna',
                        jadwal_saya: 'Jadwal Mengajar Saya',
                        km_dashboard: 'Dashboard Absensi Kelas',
                        laporan_siswa: 'Laporan Absensi Siswa',
                        guru_absensi_siswa: 'Absensi Siswa (Role Guru)',
                        guru_laporan_siswa: 'Laporan Absensi Kelas (Role Guru)',
                        koreksi_libur: 'Koreksi & Libur'
                    };
                    return titles[this.currentTab] || 'Menu';
                },
                async generateKmAccounts() {
                    let count = 0;
                    this.masterKelas.forEach(k => {
                        const className = (k.col1 || "").trim();
                        const username = `km_${className.replace(/[^a-zA-Z0-9]/g, "").toLowerCase()}`;
                        if (!this.masterUser.find(u => u.username === username)) {
                            this.masterUser.unshift({
                                id: this.generateUniqueId('km'),
                                nama: `Ketua Murid ${className}`,
                                username: username,
                                password: '123',
                                role: 'kmkelas',
                                kelas_km: className,
                                status: 'AKTIF',
                                showPassword: false
                            });
                            count++;
                        }
                    });
                    
                    if (count > 0) {
                        this.saveToLocal('USERS', this.masterUser);
                        try {
                            const cleanRecords = JSON.parse(JSON.stringify(this.masterUser));
                            await this.firestoreSaveDbTable('USERS', cleanRecords);
                            this.showToast('Berhasil', `${count} Akun KM berhasil dibuat di database.`, 'success');
                        } catch (e) {
                            this.showToast('Peringatan', 'Akun KM tersimpan lokal.', 'warning');
                        }
                    } else {
                        this.showToast('Info', 'Semua akun KM untuk kelas yang ada sudah terdaftar.', 'info');
                    }
                },
                loadKmAttendanceData() {
                    const kelas = (this.currentUser.kelas_km || "").trim();
                    const tanggal = this.selectedTanggalKM;
                    let siswaDiKelas = this.masterSiswa.filter(s => (s.col3 || "").trim() === kelas);
                    siswaDiKelas.sort((a, b) => (a.col2 || "").localeCompare(b.col2 || ""));
                    
                    this.draftAbsensiKM = siswaDiKelas.map(siswa => {
                        const existing = this.studentAttendances.find(a =>
                            String(a.siswaId) === String(siswa.id) && a.tanggal === tanggal
                        );
                        return {
                            siswaId: siswa.id,
                            nis: siswa.col1,
                            nama: siswa.col2,
                            status: existing ? existing.status : "",
                            catatan: existing ? existing.catatan : ""
                        };
                    });
                },
                get kmJadwalHariIni() {
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const dateObj = new Date(this.selectedTanggalKM);
                    if (isNaN(dateObj.getTime())) return [];
                    const hariIni = days[dateObj.getDay()];
                    const kelas = (this.currentUser.kelas_km || "").trim();
                    
                    return this.uploadedSchedules
                        .filter(s => (s.kelas || "").trim() === kelas && (s.hari || "").trim() === hariIni)
                        .sort((a,b) => (parseInt(a.jamKe) || 0) - (parseInt(b.jamKe) || 0));
                },
                get tugasHariIni() {
                    const tanggal = this.selectedTanggalKM;
                    const kelas = (this.currentUser.kelas_km || "").trim();
                    return this.attendances.filter(a => a.tanggal === tanggal && (a.kelas || "").trim() === kelas && a.status === 'TUGAS');
                },
                get kmStatistik() {
                    const total = this.draftAbsensiKM.length;
                    const sudah = this.draftAbsensiKM.filter(d => d.status && d.status !== "").length;
                    const hadir = this.draftAbsensiKM.filter(d => d.status === 'Hadir').length;
                    return { total, sudah, belum: total - sudah, hadir };
                },
                markAllPresentKM() {
                    if(!this.isKmEditAllowed()) return;
                    let count = 0;
                    this.draftAbsensiKM.forEach(d => {
                        if (!d.status || d.status === "") { d.status = 'Hadir'; count++; }
                    });
                    if(count > 0) this.showToast('Info', `${count} siswa ditandai Hadir.`, 'info');
                },
                isKmEditAllowed(showAlert = true) {
                    const today = this.getTodayDateString();
                    if (this.selectedTanggalKM < today) {
                        if (showAlert) this.showToast('Batas Waktu', 'Tidak bisa mengubah absensi hari sebelumnya.', 'error');
                        return false;
                    }
                    if (this.selectedTanggalKM > today) {
                        if (showAlert) this.showToast('Batas Waktu', 'Tidak bisa melakukan absensi untuk hari esok.', 'error');
                        return false;
                    }
                    return true;
                },
                async saveKmAttendance() {
                    if(!this.isKmEditAllowed()) return;
                    const tanggal = this.selectedTanggalKM;
                    const kelas = (this.currentUser.kelas_km || "").trim();
                    let recordsToSave = [];
                    
                    this.draftAbsensiKM.forEach(draft => {
                        if (draft.status !== "") {
                            let idx = this.studentAttendances.findIndex(a => String(a.siswaId) === String(draft.siswaId) && a.tanggal === tanggal);
                            const recordId = idx !== -1 ? this.studentAttendances[idx].id : this.generateUniqueId('st_km');
                            const record = {
                                id: recordId,
                                tanggal: tanggal,
                                kelas: kelas,
                                siswaId: draft.siswaId,
                                nis: draft.nis,
                                nama: draft.nama,
                                status: draft.status,
                                catatan: draft.catatan || '-',
                                waktuUpdate: new Date().toISOString()
                            };
                            if (idx !== -1) this.studentAttendances[idx] = record;
                            else this.studentAttendances.push(record);
                            recordsToSave.push(record);
                        }
                    });
                    
                    if (recordsToSave.length === 0) {
                        this.showToast('Info', 'Tidak ada data absensi untuk disimpan.', 'info');
                        return;
                    }
                    
                    this.studentAttendances = [...this.studentAttendances];
                    this.saveToLocal('STUDENT_ATTENDANCES', this.studentAttendances);
                    
                    try {
                        const cleanRecords = JSON.parse(JSON.stringify(recordsToSave));
                        await this.firestoreBatchSave('STUDENT_ATTENDANCES', cleanRecords);
                        this.showToast('Tersimpan', `Absensi (${recordsToSave.length} siswa) berhasil disimpan ke Database.`, 'success');
                    } catch(e) {
                        this.showToast('Peringatan', 'Data tersimpan lokal. Gagal sinkron ke database.', 'warning');
                    }
                },
                get kelasGuruSaya() {
                    if (!this.currentUser || this.currentUser.role !== 'guru') return [];
                    const namaUser = (this.currentUser.nama || "").toLowerCase().trim();
                    const jadwalSaya = this.uploadedSchedules.filter(s => {
                        if(!s.guru) return false;
                        const gNama = s.guru.toLowerCase().trim();
                        return gNama.includes(namaUser) || namaUser.includes(gNama);
                    });
                    const uniqueClasses = [...new Set(jadwalSaya.map(s => (s.kelas || "").trim()))].filter(c => c !== "");
                    return uniqueClasses.sort();
                },
                loadGuruAttendanceData() {
                    if (!this.selectedKelasGuru || this.selectedKelasGuru === "") {
                        this.draftAbsensiGuru = [];
                        return;
                    }
                    const tanggal = this.selectedTanggalGuru;
                    const kelas = this.selectedKelasGuru.trim();
                    const guruNama = this.currentUser.nama;
                    
                    let siswaDiKelas = this.masterSiswa.filter(s => (s.col3 || "").trim() === kelas);
                    siswaDiKelas.sort((a, b) => (a.col2 || "").localeCompare(b.col2 || ""));
                    
                    this.draftAbsensiGuru = siswaDiKelas.map(siswa => {
                        const existing = this.teacherStudentAttendances.find(a =>
                            String(a.siswaId) === String(siswa.id) &&
                            a.tanggal === tanggal &&
                            a.kelas === kelas &&
                            a.guru === guruNama
                        );
                        return {
                            siswaId: siswa.id,
                            nis: siswa.col1,
                            nama: siswa.col2,
                            status: existing ? existing.status : "",
                            catatan: existing ? existing.catatan : ""
                        };
                    });
                },
                isGuruEditAllowed(showAlert = true) {
                    const today = this.getTodayDateString();
                    if (this.selectedTanggalGuru < today) {
                        if (showAlert) this.showToast('Batas Waktu', 'Tidak bisa mengubah absensi hari sebelumnya.', 'error');
                        return false;
                    }
                    if (this.selectedTanggalGuru > today) {
                        if (showAlert) this.showToast('Batas Waktu', 'Tidak bisa melakukan absensi untuk hari esok.', 'error');
                        return false;
                    }
                    return true;
                },
                markAllPresentGuru() {
                    if(!this.isGuruEditAllowed()) return;
                    let count = 0;
                    this.draftAbsensiGuru.forEach(d => {
                        if (!d.status || d.status === "") { d.status = 'Hadir'; count++; }
                    });
                    if(count > 0) this.showToast('Info', `${count} siswa ditandai Hadir.`, 'info');
                },
                async saveGuruAttendance() {
                    if(!this.isGuruEditAllowed()) return;
                    if(!this.selectedKelasGuru) {
                        this.showToast('Peringatan', 'Pilih kelas terlebih dahulu.', 'warning');
                        return;
                    }
                    const tanggal = this.selectedTanggalGuru;
                    const kelas = this.selectedKelasGuru.trim();
                    const guruNama = this.currentUser.nama;
                    let recordsToSave = [];
                    
                    this.draftAbsensiGuru.forEach(draft => {
                        if (draft.status !== "") {
                            let idx = this.teacherStudentAttendances.findIndex(a =>
                                String(a.siswaId) === String(draft.siswaId) &&
                                a.tanggal === tanggal &&
                                a.kelas === kelas &&
                                a.guru === guruNama
                            );
                            const recordId = idx !== -1 ? this.teacherStudentAttendances[idx].id : this.generateUniqueId('tg_att');
                            const record = {
                                id: recordId,
                                tanggal: tanggal,
                                kelas: kelas,
                                guru: guruNama,
                                siswaId: draft.siswaId,
                                nis: draft.nis,
                                nama: draft.nama,
                                status: draft.status,
                                catatan: draft.catatan || '-',
                                waktuUpdate: new Date().toISOString()
                            };
                            if (idx !== -1) this.teacherStudentAttendances[idx] = record;
                            else this.teacherStudentAttendances.push(record);
                            recordsToSave.push(record);
                        }
                    });
                    
                    if (recordsToSave.length === 0) {
                        this.showToast('Info', 'Tidak ada data absensi untuk disimpan.', 'info');
                        return;
                    }
                    
                    this.teacherStudentAttendances = [...this.teacherStudentAttendances];
                    this.saveToLocal('TEACHER_STUDENT_ATTENDANCES', this.teacherStudentAttendances);
                    
                    try {
                        const cleanRecords = JSON.parse(JSON.stringify(recordsToSave));
                        await this.firestoreBatchSave('TEACHER_STUDENT_ATTENDANCES', cleanRecords);
                        this.showToast('Tersimpan', `Absensi kelas berhasil disimpan ke Database.`, 'success');
                    } catch (e) {
                        this.showToast('Peringatan', 'Data tersimpan lokal. Gagal sinkron ke database.', 'warning');
                    }
                },
                get laporanSiswaGuruList() {
                    const start = this.reportSiswaGuruStartDate;
                    const end = this.reportSiswaGuruEndDate;
                    const kls = this.reportSiswaGuruKelas;
                    const guruNama = this.currentUser.nama;
                    if (!start || !end) return [];
                    return this.teacherStudentAttendances.filter(a => {
                        const dateValid = a.tanggal >= start && a.tanggal <= end;
                        const guruValid = a.guru === guruNama;
                        const kelasValid = (kls === 'Semua') ? true : a.kelas === kls;
                        return dateValid && guruValid && kelasValid;
                    }).sort((a, b) => (a.tanggal || "").localeCompare(b.tanggal || "") || (a.kelas || "").localeCompare(b.kelas || "") || (a.nama || "").localeCompare(b.nama || ""));
                },
                downloadLaporanSiswaGuruExcel() {
                    const list = this.laporanSiswaGuruList;
                    if(list.length === 0) {
                        this.showToast('Peringatan', 'Tidak ada data untuk di-export.', 'warning');
                        return;
                    }
                    const ws_data = [
                        ["LAPORAN ABSENSI SISWA OLEH GURU"],
                        ["Nama Guru", this.currentUser.nama],
                        ["Periode", `${this.reportSiswaGuruStartDate} s/d ${this.reportSiswaGuruEndDate}`],
                        ["Filter Kelas", this.reportSiswaGuruKelas],
                        [],
                        ["Tanggal", "Kelas", "NIS", "Nama Lengkap", "Status", "Catatan"]
                    ];
                    list.forEach(s => ws_data.push([s.tanggal, s.kelas, s.nis, s.nama, s.status, s.catatan]));
                    const ws = XLSX.utils.aoa_to_sheet(ws_data);
                    ws['!cols'] = [{wch: 15}, {wch: 10}, {wch: 15}, {wch: 35}, {wch: 15}, {wch: 30}];
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Laporan Absensi Kelas");
                    XLSX.writeFile(wb, `Absensi_Guru_${this.reportSiswaGuruKelas}_${this.reportSiswaGuruStartDate}_${this.reportSiswaGuruEndDate}.xlsx`);
                    this.showToast('Berhasil', 'Laporan Absensi Siswa diekspor.', 'success');
                },
                get filteredGuruSchedules() {
                    if (!this.currentUser || this.currentUser.role !== 'guru') return [];
                    const namaUser = (this.currentUser.nama || "").toLowerCase().trim();
                    const jadwalSaya = this.uploadedSchedules.filter(s => {
                        if(!s.guru) return false;
                        const gNama = s.guru.toLowerCase().trim();
                        return gNama.includes(namaUser) || namaUser.includes(gNama);
                    });
                    let jadwalHariIni = jadwalSaya.filter(s => (s.hari || "").trim() === this.selectedDayGuru);
                    return jadwalHariIni.sort((a, b) => (parseInt(a.jamKe) || 0) - (parseInt(b.jamKe) || 0));
                },
                getTodayGuruSchedules() {
                    if (!this.currentUser || this.currentUser.role !== 'guru') return [];
                    const namaUser = (this.currentUser.nama || "").toLowerCase().trim();
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const currentDayName = days[new Date().getDay()];
                    
                    return this.uploadedSchedules.filter(s => {
                        if(!s.guru) return false;
                        const gNama = s.guru.toLowerCase().trim();
                        return (s.hari || "").trim() === currentDayName && (gNama.includes(namaUser) || namaUser.includes(gNama));
                    }).sort((a,b) => (parseInt(a.jamKe) || 0) - (parseInt(b.jamKe) || 0));
                },
                getGroupedTodayGuruSchedules() {
                    const schedules = this.getTodayGuruSchedules();
                    const grouped = {};
                    schedules.forEach(s => {
                        const key = s.kelas;
                        if (!grouped[key]) {
                            grouped[key] = {
                                kelas: s.kelas,
                                mapels: [],
                                jamKeList: [],
                                waktuMulai: s.waktu,
                                waktuSelesai: s.waktu,
                                jadwalIds: []
                            };
                        }
                        grouped[key].mapels.push(s.mapel);
                        grouped[key].jamKeList.push(s.jamke);
                        grouped[key].jadwalIds.push(s.id);
                        if (s.waktu && s.waktu.includes('-')) {
                            const parts = s.waktu.split('-');
                            if (parts.length === 2) {
                                grouped[key].waktuSelesai = parts[1].trim();
                            }
                        }
                    });
                    return Object.values(grouped);
                },
                hasGroupAttended(jadwalIds) {
                    const today = this.getTodayDateString();
                    return jadwalIds.some(id => {
                        const s = this.uploadedSchedules.find(item => String(item.id) === String(id));
                        return this.attendances.some(a =>
                            (String(a.jadwalId) === String(id) && a.tanggal === today) ||
                            (s && a.tanggal === today && (a.guru || "").trim().toLowerCase() === (s.guru || "").trim().toLowerCase() && (a.kelas || "").trim().toLowerCase() === (s.kelas || "").trim().toLowerCase())
                        );
                    });
                },
                getGroupAttendanceStatus(jadwalIds) {
                    const today = this.getTodayDateString();
                    for (let id of jadwalIds) {
                        const s = this.uploadedSchedules.find(item => String(item.id) === String(id));
                        const att = this.attendances.find(a =>
                            (String(a.jadwalId) === String(id) && a.tanggal === today) ||
                            (s && a.tanggal === today && (a.guru || "").trim().toLowerCase() === (s.guru || "").trim().toLowerCase() && (a.kelas || "").trim().toLowerCase() === (s.kelas || "").trim().toLowerCase())
                        );
                        if (att) return att.status;
                    }
                    return "";
                },
                isGroupSchedulePassed(waktuString) {
                    const now = new Date(this.currentTimeTrigger);
                    const currentTotal = now.getHours() * 60 + now.getMinutes();
                    return currentTotal >= 960;
                },
                lihatTugas(data) {
                    this.tugasDetailData = data;
                    this.showTugasDetailModal = true;
                },
                openTugasModal(jadwalIds) {
                    this.tugasForm = { jadwalIds: jadwalIds, deskripsi: "" };
                    this.showTugasModal = true;
                },
                submitTugas() {
                    if (!this.tugasForm.deskripsi.trim()) {
                        this.showToast('Gagal', 'Deskripsi tugas tidak boleh kosong.', 'error');
                        return;
                    }
                    this.markGroupAttendance(this.tugasForm.jadwalIds, 'TUGAS', this.tugasForm.deskripsi);
                    this.showTugasModal = false;
                },
                async markGroupAttendance(jadwalIds, status, deskripsiTugas = '-') {
                    const today = this.getTodayDateString();
                    let recordsToSave = [];
                    
                    jadwalIds.forEach(jadwalId => {
                        const schedule = this.uploadedSchedules.find(s => String(s.id) === String(jadwalId));
                        if(!schedule) return;
                        if (this.isGroupSchedulePassed(schedule.waktu)) {
                            this.showToast('Gagal', 'Waktu konfirmasi telah lewat (16:00). Konfirmasi dikunci.', 'error');
                            return;
                        }
                        
                        let existingIdx = this.attendances.findIndex(a => String(a.jadwalId) === String(jadwalId) && a.tanggal === today);
                        let record;
                        if (existingIdx !== -1) {
                            this.attendances[existingIdx].status = status;
                            if (status === 'TUGAS') this.attendances[existingIdx].deskripsiTugas = deskripsiTugas;
                            record = this.attendances[existingIdx];
                        } else {
                            record = {
                                id: this.generateUniqueId('att_grp'),
                                jadwalId: jadwalId,
                                tanggal: today,
                                hari: schedule.hari,
                                guru: schedule.guru,
                                kelas: schedule.kelas,
                                mapel: schedule.mapel,
                                jamke: schedule.jamke,
                                status: status,
                                deskripsiTugas: (status === 'TUGAS') ? deskripsiTugas : '-'
                            };
                            this.attendances.push(record);
                        }
                        recordsToSave.push(record);
                    });
                    
                    if (recordsToSave.length > 0) {
                        this.attendances = [...this.attendances];
                        this.saveToLocal('ATTENDANCES', this.attendances);
                        try {
                            const cleanPayload = JSON.parse(JSON.stringify(recordsToSave));
                            await this.firestoreBatchSave('ATTENDANCES', cleanPayload);
                            this.showToast('Tercatat', `Status ${status} berhasil disimpan ke Database.`, 'success');
                        } catch (e) {
                            this.showToast('Peringatan', 'Status tersimpan lokal.', 'warning');
                        }
                    }
                },
                async markAttendance(jadwalId, status) {
                    const today = this.getTodayDateString();
                    const schedule = this.uploadedSchedules.find(s => String(s.id) === String(jadwalId));
                    if(!schedule) return;
                    
                    const relatedSchedules = this.uploadedSchedules.filter(s =>
                        (s.hari || "").trim() === schedule.hari.trim() &&
                        (s.guru || "").trim().toLowerCase() === (schedule.guru || "").trim().toLowerCase() &&
                        (s.kelas || "").trim().toLowerCase() === (schedule.kelas || "").trim().toLowerCase()
                    );
                    
                    let recordsToSave = [];
                    const schedulesToProcess = relatedSchedules.length > 0 ? relatedSchedules : [schedule];
                    
                    schedulesToProcess.forEach(targetSch => {
                        let existingIdx = this.attendances.findIndex(a => String(a.jadwalId) === String(targetSch.id) && a.tanggal === today);
                        let record;
                        if (existingIdx !== -1) {
                            this.attendances[existingIdx].status = status;
                            record = this.attendances[existingIdx];
                        } else {
                            record = {
                                id: this.generateUniqueId('att_rt'),
                                jadwalId: targetSch.id,
                                tanggal: today,
                                hari: targetSch.hari,
                                guru: targetSch.guru,
                                kelas: targetSch.kelas,
                                mapel: targetSch.mapel,
                                jamke: targetSch.jamke,
                                status: status,
                                deskripsiTugas: '-'
                            };
                            this.attendances.push(record);
                        }
                        recordsToSave.push(record);
                    });
                    
                    this.attendances = [...this.attendances];
                    this.saveToLocal('ATTENDANCES', this.attendances);
                    try {
                        const cleanRecords = JSON.parse(JSON.stringify(recordsToSave));
                        await this.firestoreBatchSave('ATTENDANCES', cleanRecords);
                        this.showToast('Tercatat', `Status ${status} untuk seluruh jam kelas tersimpan di Database.`, 'success');
                    } catch (e) {
                        this.showToast('Peringatan', 'Data tersimpan lokal.', 'warning');
                    }
                },
                getRealtimeDashboard() {
                    const trigger = this.currentTimeTrigger;
                    const today = this.getTodayDateString();
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const currentDayName = days[new Date(trigger).getDay()];
                    let todaySchedules = this.uploadedSchedules.filter(s => (s.hari || "").trim() === currentDayName);
                    todaySchedules = todaySchedules.filter(s => this.isTimeOngoing(s.waktu));
                    
                    const uniqueClasses = {};
                    todaySchedules.forEach(s => {
                        if (!uniqueClasses[s.kelas]) {
                            let att = this.attendances.find(a => String(a.jadwalId) === String(s.id) && a.tanggal === today);
                            if (!att) {
                                att = this.attendances.find(a =>
                                    a.tanggal === today &&
                                    (a.guru || "").trim().toLowerCase() === (s.guru || "").trim().toLowerCase() &&
                                    (a.kelas || "").trim().toLowerCase() === (s.kelas || "").trim().toLowerCase()
                                );
                            }
                            uniqueClasses[s.kelas] = {
                                ...s,
                                status: att ? att.status : 'BELUM',
                                deskripsiTugas: att ? att.deskripsiTugas : '-'
                            };
                        }
                    });
                    
                    return Object.values(uniqueClasses).sort((a, b) => (a.kelas || "").localeCompare(b.kelas || ""));
                },
                sortByMonitoring(column) {
                    if (this.sortMonitoringColumn === column) {
                        this.sortMonitoringDirection = this.sortMonitoringDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortMonitoringColumn = column;
                        this.sortMonitoringDirection = 'asc';
                    }
                },
                getTodayMonitoring() {
                    const trigger = this.currentTimeTrigger;
                    const today = this.getTodayDateString();
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const currentDayName = days[new Date(trigger).getDay()];
                    let todaySchedules = this.uploadedSchedules.filter(s => (s.hari || "").trim() === currentDayName);
                    
                    if (this.searchMonitoringGuru) {
                        const queryGuru = this.searchMonitoringGuru.toLowerCase().trim();
                        todaySchedules = todaySchedules.filter(s => (s.guru || "").toLowerCase().includes(queryGuru));
                    }
                    if (this.searchMonitoringKelas) {
                        const queryKelas = this.searchMonitoringKelas.trim();
                        todaySchedules = todaySchedules.filter(s => (s.kelas || "").trim() === queryKelas);
                    }
                    
                    let result = todaySchedules.map(s => {
                        let att = this.attendances.find(a => String(a.jadwalId) === String(s.id) && a.tanggal === today);
                        if (!att) {
                            att = this.attendances.find(a =>
                                a.tanggal === today &&
                                (a.guru || "").trim().toLowerCase() === (s.guru || "").trim().toLowerCase() &&
                                (a.kelas || "").trim().toLowerCase() === (s.kelas || "").trim().toLowerCase()
                            );
                        }
                        let calculatedStatus = att ? att.status : 'BELUM';
                        let deskripsiTugas = att ? att.deskripsiTugas : '-';
                        if (calculatedStatus === 'BELUM' && this.isGroupSchedulePassed(s.waktu)) {
                            calculatedStatus = 'ALPA';
                        }
                        return { ...s, status: calculatedStatus, deskripsiTugas: deskripsiTugas };
                    });
                    
                    result.sort((a, b) => {
                        let valA, valB;
                        switch (this.sortMonitoringColumn) {
                            case 'guru': valA = (a.guru || "").toLowerCase(); valB = (b.guru || "").toLowerCase(); break;
                            case 'kelas_mapel': valA = ((a.kelas || "") + ' ' + (a.mapel || "")).toLowerCase(); valB = ((b.kelas || "") + ' ' + (b.mapel || "")).toLowerCase(); break;
                            case 'jam_waktu': valA = parseInt(a.jamKe) || 0; valB = parseInt(b.jamKe) || 0; break;
                            case 'status': valA = (a.status === 'BELUM' ? 'BELUM ADA INFO' : a.status).toLowerCase(); valB = (b.status === 'BELUM' ? 'BELUM ADA INFO' : b.status).toLowerCase(); break;
                            default: valA = (a.guru || "").toLowerCase(); valB = (b.guru || "").toLowerCase();
                        }
                        let comparison = 0;
                        if (valA < valB) comparison = -1;
                        if (valA > valB) comparison = 1;
                        if (comparison === 0 && this.sortMonitoringColumn !== 'jam_waktu') {
                            const jamA = parseInt(a.jamKe) || 0; const jamB = parseInt(b.jamKe) || 0;
                            comparison = jamA - jamB;
                        }
                        return this.sortMonitoringDirection === 'desc' ? (comparison * -1) : comparison;
                    });
                    return result;
                },
                get laporanKehadiran() {
                    const start = this.reportStartDate;
                    const end = this.reportEndDate;
                    if (!start || !end) return [];
                    
                    const normalizeDate = (val) => {
                        if (!val) return "";
                        val = String(val).trim();
                        if (val.includes('T')) val = val.split('T')[0];
                        if (val.includes('/')) {
                            const p = val.split('/');
                            if (p.length === 3) {
                                if (p[2].length === 4) {
                                    return `${p[2]}-${String(p[1]).padStart(2, '0')}-${String(p[0]).padStart(2, '0')}`;
                                } else if (p[0].length === 4) {
                                    return `${p[0]}-${String(p[1]).padStart(2, '0')}-${String(p[2]).padStart(2, '0')}`;
                                }
                            }
                        }
                        return val;
                    };
                    
                    const parseDateObj = (str) => {
                        const norm = normalizeDate(str);
                        const [y, m, d] = norm.split('-').map(Number);
                        return new Date(y, m - 1, d);
                    };
                    
                    const TANGGAL_MULAI_SISTEM = parseDateObj('2026-09-13');
                    let pickedStart = parseDateObj(start);
                    const startDate = pickedStart < TANGGAL_MULAI_SISTEM ? TANGGAL_MULAI_SISTEM : pickedStart;
                    const endDate = parseDateObj(end);
                    const todayNorm = normalizeDate(this.getTodayDateString());
                    const cleanStr = (s) => String(s || "").toLowerCase().replace(/[^a-z0-9]/g, "");
                    const stats = {};
                    
                    this.masterGuru.forEach(g => {
                        let nama = (g.col1 || "").trim();
                        if (nama) stats[nama] = { nama: nama, totalJadwal: 0, hadir: 0, tugas: 0, tidakHadir: 0 };
                    });
                    
                    const daysArr = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    let curr = new Date(startDate);
                    
                    while (curr <= endDate) {
                        const y = curr.getFullYear();
                        const m = String(curr.getMonth() + 1).padStart(2, '0');
                        const d = String(curr.getDate()).padStart(2, '0');
                        const dateStr = `${y}-${m}-${d}`;
                        const dayName = daysArr[curr.getDay()];
                        const isHoliday = this.holidays.some(h => h.tanggal === dateStr);
                        
                        const daySchedules = this.uploadedSchedules.filter(s => (s.hari || "").trim().toLowerCase() === dayName.toLowerCase());
                        
                        daySchedules.forEach(s => {
                            const guruNama = (s.guru || "").trim();
                            if (!guruNama) return;
                            if (!stats[guruNama]) {
                                stats[guruNama] = { nama: guruNama, totalJadwal: 0, hadir: 0, tugas: 0, tidakHadir: 0 };
                            }
                            if (!isHoliday) stats[guruNama].totalJadwal++;
                            
                            const targetGuruClean = cleanStr(guruNama);
                            const targetKelasClean = cleanStr(s.kelas);
                            
                            let att = this.attendances.find(a => {
                                const aDateNorm = normalizeDate(a.tanggal);
                                if (aDateNorm !== dateStr) return false;
                                if (String(a.jadwalId).trim() === String(s.id).trim()) return true;
                                const aGuruClean = cleanStr(a.guru);
                                const aKelasClean = cleanStr(a.kelas);
                                return (aGuruClean === targetGuruClean && aKelasClean === targetKelasClean);
                            });
                            
                            if (att) {
                                const st = String(att.status || "").toUpperCase();
                                if (st === 'HADIR') stats[guruNama].hadir++;
                                else if (st === 'TUGAS') stats[guruNama].tugas++;
                                else if (st === 'ALPA') { if(!isHoliday) stats[guruNama].tidakHadir++; }
                                else if (dateStr < todayNorm) { if(!isHoliday) stats[guruNama].tidakHadir++; }
                            } else {
                                if (dateStr < todayNorm) {
                                    if(!isHoliday) stats[guruNama].tidakHadir++;
                                }
                            }
                        });
                        curr.setDate(curr.getDate() + 1);
                    }
                    return Object.values(stats).sort((a, b) => a.nama.localeCompare(b.nama));
                },
                get reportHighlights() {
                    const reportData = this.laporanKehadiran;
                    if(!reportData.length) return { palingRajin: '-', seringTugas: '-', seringAbsen: '-' };
                    let maxHadir = 0; let palingRajin = '-';
                    let maxTugas = 0; let seringTugas = '-';
                    let maxAbsen = 0; let seringAbsen = '-';
                    reportData.forEach(r => {
                        if(r.hadir > maxHadir) { maxHadir = r.hadir; palingRajin = r.nama; }
                        if(r.tugas > maxTugas) { maxTugas = r.tugas; seringTugas = r.nama; }
                        if(r.tidakHadir > maxAbsen) { maxAbsen = r.tidakHadir; seringAbsen = r.nama; }
                    });
                    return { palingRajin, seringTugas, seringAbsen };
                },
                // ===== FUNGSI KOREKSI & LIBUR ===== //
                async simpanLibur() {
                    if(!this.liburForm.tanggal || !this.liburForm.keterangan) {
                        this.showToast('Gagal', 'Lengkapi tanggal dan keterangan libur.', 'error');
                        return;
                    }
                    const record = {
                        id: this.generateUniqueId('libur'),
                        tanggal: this.liburForm.tanggal,
                        keterangan: this.liburForm.keterangan
                    };
                    this.holidays.push(record);
                    this.saveToLocal('HOLIDAYS', this.holidays);
                    this.liburForm = { tanggal: "", keterangan: "" };
                    try {
                        await window.fb.setDoc(window.fb.doc(window.db, 'HOLIDAYS', record.id), record);
                        this.showToast('Sukses', 'Hari libur berhasil ditambahkan.', 'success');
                    } catch(e) {
                        this.showToast('Peringatan', 'Tersimpan lokal.', 'warning');
                    }
                },
                async hapusLibur(id) {
                    this.holidays = this.holidays.filter(h => h.id !== id);
                    this.saveToLocal('HOLIDAYS', this.holidays);
                    try {
                        await window.fb.deleteDoc(window.fb.doc(window.db, 'HOLIDAYS', id));
                        this.showToast('Sukses', 'Hari libur dibatalkan.', 'success');
                    } catch(e) {
                        this.showToast('Peringatan', 'Terhapus lokal.', 'warning');
                    }
                },
                get todayHoliday() {
                    const today = this.getTodayDateString();
                    return this.holidays.find(h => h.tanggal === today);
                },
                get koreksiGuruList() {
                    if(!this.koreksiGuruForm.tanggal || !this.koreksiGuruForm.guru) return [];
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const dateObj = new Date(this.koreksiGuruForm.tanggal);
                    if(isNaN(dateObj)) return [];
                    const dayName = days[dateObj.getDay()];
                    const targetGuruClean = String(this.koreksiGuruForm.guru || "").trim().toLowerCase();
                    
                    let schedules = this.uploadedSchedules.filter(s =>
                        (s.hari || "").trim() === dayName &&
                        (s.guru || "").trim().toLowerCase() === targetGuruClean
                    ).sort((a,b) => (parseInt(a.jamKe)||0) - (parseInt(b.jamKe)||0));
                    
                    return schedules.map(s => {
                        let att = this.attendances.find(a =>
                            a.tanggal === this.koreksiGuruForm.tanggal &&
                            (String(a.jadwalId) === String(s.id) ||
                            ((a.guru||"").trim().toLowerCase() === targetGuruClean && (a.kelas||"").trim() === (s.kelas||"").trim()))
                        );
                        return {
                            jadwal: s,
                            att: att,
                            status: att ? att.status : 'BELUM ADA INFO'
                        };
                    });
                },
                async ubahKeHadirAdmin(item) {
                    const tanggal = this.koreksiGuruForm.tanggal;
                    let record;
                    if(item.att) {
                        record = {...item.att, status: 'HADIR', deskripsiTugas: '-'};
                        let idx = this.attendances.findIndex(a => a.id === item.att.id);
                        if(idx !== -1) this.attendances[idx] = record;
                    } else {
                        record = {
                            id: this.generateUniqueId('att'),
                            jadwalId: item.jadwal.id,
                            tanggal: tanggal,
                            hari: item.jadwal.hari,
                            guru: item.jadwal.guru,
                            kelas: item.jadwal.kelas,
                            mapel: item.jadwal.mapel,
                            jamke: item.jadwal.jamke,
                            status: 'HADIR',
                            deskripsiTugas: '-'
                        };
                        this.attendances.push(record);
                    }
                    this.saveToLocal('ATTENDANCES', this.attendances);
                    try {
                        await window.fb.setDoc(window.fb.doc(window.db, 'ATTENDANCES', record.id), record);
                        this.showToast('Sukses', 'Status diubah menjadi HADIR.', 'success');
                    } catch(e) {
                        this.showToast('Peringatan', 'Tersimpan lokal.', 'warning');
                    }
                },
                get laporanAdminSiswaHistory() {
                    const start = this.reportAdminSiswaStartDate;
                    const end = this.reportAdminSiswaEndDate;
                    const kls = this.filterKelasLaporanSiswa;
                    if (!start || !end) return [];
                    
                    let records = this.studentAttendances.filter(a => a.tanggal >= start && a.tanggal <= end);
                    if (kls !== 'Semua') {
                        records = records.filter(a => (a.kelas || "").trim() === kls.trim());
                    }
                    return records.sort((a, b) => (a.tanggal || "").localeCompare(b.tanggal || "") || (a.kelas || "").localeCompare(b.kelas || "") || (a.nama || "").localeCompare(b.nama || ""));
                },
                get statistikSiswaAdmin() {
                    const list = this.laporanAdminSiswaHistory;
                    const stats = { Total: list.length, Hadir: 0, Sakit: 0, Izin: 0, Alpa: 0, Terlambat: 0 };
                    list.forEach(item => {
                        if (stats[item.status] !== undefined) stats[item.status]++;
                    });
                    const kehadiranCount = stats.Hadir + stats.Terlambat;
                    stats.Persentase = stats.Total > 0 ? ((kehadiranCount/stats.Total) * 100).toFixed(1) : 0;
                    return stats;
                },
                renderAdminChart() {
                    if (this.currentTab !== 'laporan_siswa') return;
                    const ctx = document.getElementById('attendanceChart');
                    if (!ctx) return;
                    const stats = this.statistikSiswaAdmin;
                    const data = {
                        labels: ['Hadir', 'Terlambat', 'Sakit', 'Izin', 'Alpa'],
                        datasets: [{
                            label: 'Jumlah Siswa',
                            data: [stats.Hadir, stats.Terlambat, stats.Sakit, stats.Izin, stats.Alpa],
                            backgroundColor: ['rgba(34, 197, 94, 0.7)', 'rgba(234, 179, 8, 0.7)', 'rgba(249, 115, 22, 0.7)', 'rgba(59, 130, 246, 0.7)', 'rgba(239, 68, 68, 0.7)'],
                            borderColor: ['rgb(34, 197, 94)', 'rgb(234, 179, 8)', 'rgb(249, 115, 22)', 'rgb(59, 130, 246)', 'rgb(239, 68, 68)'],
                            borderWidth: 1
                        }]
                    };
                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }
                    this.chartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                            animation: { duration: 400 }
                        }
                    });
                },
                editKoreksiSiswa(siswa) {
                    this.formData = {
                        siswaId: siswa.siswaId,
                        nis: siswa.nis,
                        nama: siswa.nama,
                        kelas: siswa.kelas,
                        status: siswa.status,
                        catatan: siswa.catatan,
                        attId: siswa.id,
                        tanggal: siswa.tanggal
                    };
                    this.modalMode = 'koreksi_siswa';
                    this.showModal = true;
                },
                async saveKoreksiSiswa() {
                    const tanggal = this.formData.tanggal;
                    let record = {
                        id: this.formData.attId,
                        tanggal: tanggal,
                        kelas: this.formData.kelas,
                        siswaId: this.formData.siswaId,
                        nis: this.formData.nis,
                        nama: this.formData.nama,
                        status: this.formData.status,
                        catatan: this.formData.catatan || '-',
                        waktuUpdate: new Date().toISOString()
                    };
                    
                    let idx = this.studentAttendances.findIndex(a => String(a.id) === String(record.id));
                    if (idx !== -1) {
                        this.studentAttendances[idx] = record;
                    }
                    this.saveToLocal('STUDENT_ATTENDANCES', this.studentAttendances);
                    try {
                        const cleanPayload = JSON.parse(JSON.stringify(record));
                        await window.fb.setDoc(window.fb.doc(window.db, 'STUDENT_ATTENDANCES', cleanPayload.id), cleanPayload);
                        this.showModal = false;
                        this.showToast('Sukses', 'Koreksi absensi tersimpan di database.', 'success');
                        this.renderAdminChart();
                    } catch (e) {
                        this.showModal = false;
                        this.showToast('Peringatan', 'Tersimpan lokal. Gagal sinkron ke database.', 'warning');
                        this.renderAdminChart();
                    }
                },
                printPDF() { window.print(); },
                downloadLaporanExcel() {
                    const reportData = this.laporanKehadiran;
                    const ws_data = [
                        ["LAPORAN KEHADIRAN GURU SMAN 2 CIKUT"],
                        ["Periode", `${this.reportStartDate} s/d ${this.reportEndDate}`],
                        [],
                        ["Nama Guru", "Total Jadwal", "Hadir", "Tugas", "Alpa"]
                    ];
                    reportData.forEach(r => ws_data.push([r.nama, r.totalJadwal, r.hadir, r.tugas, r.tidakHadir]));
                    const ws = XLSX.utils.aoa_to_sheet(ws_data);
                    ws['!cols'] = [{wch: 35}, {wch: 15}, {wch: 10}, {wch: 10}, {wch: 20}];
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Laporan Kehadiran");
                    XLSX.writeFile(wb, `Laporan_Guru_${this.reportStartDate}_${this.reportEndDate}.xlsx`);
                    this.showToast('Berhasil', 'Laporan Excel diunduh.', 'success');
                },
                downloadLaporanSiswaExcel() {
                    const list = this.laporanAdminSiswaHistory;
                    const stats = this.statistikSiswaAdmin;
                    const ws_data = [
                        ["HISTORY ABSENSI SISWA SMAN 2 CIKUT"],
                        ["Periode", `${this.reportAdminSiswaStartDate} s/d ${this.reportAdminSiswaEndDate}`],
                        ["Filter Kelas", this.filterKelasLaporanSiswa],
                        ["Persentase Kehadiran", `${stats.Persentase}%`],
                        [],
                        ["Tanggal", "Kelas", "NIS", "Nama Lengkap", "Status", "Catatan"]
                    ];
                    list.forEach(s => ws_data.push([s.tanggal, s.kelas, s.nis, s.nama, s.status, s.catatan]));
                    const ws = XLSX.utils.aoa_to_sheet(ws_data);
                    ws['!cols'] = [{wch: 15}, {wch: 10}, {wch: 15}, {wch: 35}, {wch: 15}, {wch: 30}];
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "History Absensi Siswa");
                    XLSX.writeFile(wb, `History_Absensi_Siswa_${this.filterKelasLaporanSiswa}_${this.reportAdminSiswaStartDate}_${this.reportAdminSiswaEndDate}.xlsx`);
                    this.showToast('Berhasil', 'Laporan History Absensi Siswa diekspor.', 'success');
                },
                exportDataExcel() {
                    let ws_data = []; let cols = []; let fileName = "";
                    const dataList = this.getActiveDataList();
                    if (dataList.length === 0) { this.showToast('Gagal', 'Tidak ada data untuk diekspor.', 'error'); return; }
                    
                    if (this.currentTab === 'user') {
                        ws_data.push(["Nama Lengkap", "Username", "Role", "Kelas (KM)", "Status"]);
                        dataList.forEach(item => ws_data.push([item.nama, item.username, item.role, item.kelas_km || '-', item.status]));
                        cols = [{wch: 30}, {wch: 25}, {wch: 15}, {wch: 15}, {wch: 15}];
                        fileName = "Data_Manajemen_User.xlsx";
                    } else if (this.currentTab === 'jadwal') {
                        ws_data.push(["Hari", "Jam Ke", "Waktu", "Kelas", "Mata Pelajaran", "Guru Pengampu"]);
                        dataList.forEach(item => ws_data.push([item.hari, item.jamke, item.waktu, item.kelas, item.mapel, item.guru]));
                        cols = [{wch: 15}, {wch: 10}, {wch: 20}, {wch: 15}, {wch: 30}, {wch: 35}];
                        fileName = "Data_Jadwal_Pelajaran.xlsx";
                    } else if (this.currentTab === 'master_siswa') {
                        ws_data.push(["NIS/NISN", "Nama Lengkap Siswa", "Kelas", "Agama", "Status"]);
                        dataList.forEach(item => ws_data.push([item.col1, item.col2, item.col3, item.col4 || '-', item.status]));
                        cols = [{wch: 15}, {wch: 35}, {wch: 15}, {wch: 15}, {wch: 15}];
                        fileName = "Data_Master_Siswa.xlsx";
                    } else {
                        let title1 = this.getCol1Label(); let title2 = this.getCol2Label();
                        ws_data.push([title1, title2, "Status"]);
                        dataList.forEach(item => ws_data.push([item.col1, item.col2, item.status]));
                        cols = [{wch: 35}, {wch: 35}, {wch: 15}];
                        if(this.currentTab === 'master_guru') fileName = "Data_Master_Guru.xlsx";
                        if(this.currentTab === 'master_kelas') fileName = "Data_Master_Kelas.xlsx";
                        if(this.currentTab === 'master_mapel') fileName = "Data_Master_Mapel.xlsx";
                    }
                    const ws = XLSX.utils.aoa_to_sheet(ws_data); ws['!cols'] = cols;
                    const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, "Data");
                    XLSX.writeFile(wb, fileName);
                    this.showToast('Berhasil', 'Data berhasil diekspor ke Excel.', 'success');
                },
                getActiveDataList() {
                    if(this.currentTab === 'master_guru') return this.masterGuru.sort((a, b) => (a.col1 || "").localeCompare(b.col1 || ""));
                    if(this.currentTab === 'master_kelas') return this.masterKelas.sort((a, b) => (a.col1 || "").localeCompare(b.col1 || ""));
                    if(this.currentTab === 'master_mapel') return this.masterMapel.sort((a, b) => (a.col1 || "").localeCompare(b.col1 || ""));
                    if(this.currentTab === 'master_siswa') return this.masterSiswa.sort((a, b) => (a.col2 || "").localeCompare(b.col2 || ""));
                    if(this.currentTab === 'user') return this.masterUser.sort((a, b) => (a.nama || "").localeCompare(b.nama || ""));
                    if(this.currentTab === 'jadwal') return this.uploadedSchedules.sort((a, b) => (a.mapel || "").localeCompare(b.mapel || ""));
                    return [];
                },
                openModal(type, item = null) {
                    this.modalMode = type;
                    this.showModal = true;
                    if (type === 'edit' && item) {
                        this.editId = item.id;
                        this.formData = JSON.parse(JSON.stringify(item));
                    } else {
                        this.editId = null;
                        if (this.currentTab === 'user') {
                            this.formData = { nama: "", username: "", password: "", role: 'guru', kelas_km: "" };
                        } else if (this.currentTab === 'jadwal') {
                            this.formData = {hari: 'Senin', jamke: "", waktu: "", kelas: "", mapel: "", guru: "" };
                        } else if (this.currentTab === 'master_siswa') {
                            this.formData = { col1: "", col2: "", col3: "", col4: "" };
                        } else {
                            this.formData = { col1: "", col2: "" };
                        }
                    }
                },
                async saveData() {
                    if(this.modalMode === 'koreksi_siswa') {
                        this.saveKoreksiSiswa();
                        return;
                    }
                    this.isProcessingData = true;
                    let targetArray = []; let tableName = ""; let localKey = "";
                    if(this.currentTab === 'master_guru') { targetArray = this.masterGuru; tableName = 'TEACHERS'; localKey = 'TEACHERS'; }
                    if(this.currentTab === 'master_kelas') { targetArray = this.masterKelas; tableName = 'CLASSES'; localKey = 'CLASSES'; }
                    if(this.currentTab === 'master_mapel') { targetArray = this.masterMapel; tableName = 'SUBJECTS'; localKey = 'SUBJECTS'; }
                    if(this.currentTab === 'master_siswa') { targetArray = this.masterSiswa; tableName = 'STUDENTS'; localKey = 'STUDENTS'; }
                    if(this.currentTab === 'user') { targetArray = this.masterUser; tableName = 'USERS'; localKey = 'USERS'; }
                    if(this.currentTab === 'jadwal') { targetArray = this.uploadedSchedules; tableName = 'SCHEDULES'; localKey = 'SCHEDULES'; }
                    
                    let recordToSave = {};
                    try {
                        if (this.modalMode === 'tambah') {
                            recordToSave = {...this.formData, id: this.generateUniqueId(tableName.toLowerCase()), status: 'AKTIF' };
                            if (this.currentTab === 'user') {
                                recordToSave.showPassword = false;
                                recordToSave.kelas_km = (recordToSave.kelas_km || "").trim();
                            }
                            targetArray.unshift(recordToSave);
                            this.saveToLocal(localKey, targetArray);
                            const payload = JSON.parse(JSON.stringify(recordToSave));
                            await window.fb.setDoc(window.fb.doc(window.db, tableName, payload.id), payload);
                            this.showToast('Sukses', 'Data baru tersimpan di database.', 'success');
                        } else if (this.modalMode === 'edit') {
                            let index = targetArray.findIndex(i => String(i.id) === String(this.editId));
                            if (index !== -1) {
                                recordToSave = {...targetArray[index], ...this.formData };
                                if (this.currentTab === 'user') {
                                    recordToSave.kelas_km = (recordToSave.kelas_km || "").trim();
                                }
                                targetArray[index] = recordToSave;
                                this.saveToLocal(localKey, targetArray);
                                const payload = JSON.parse(JSON.stringify(recordToSave));
                                await window.fb.setDoc(window.fb.doc(window.db, tableName, payload.id), payload);
                                this.showToast('Sukses', 'Perubahan data berhasil disimpan.', 'success');
                            }
                        }
                        this.showModal = false;
                    } catch (error) {
                        this.showToast('Peringatan', 'Tersimpan lokal. Gagal sinkron ke database.', 'warning');
                        this.showModal = false;
                    }
                    this.isProcessingData = false;
                },
                getCol1Label() {
                    if(this.currentTab === 'master_guru') return 'Nama Guru (Gelar Lengkap)';
                    if(this.currentTab === 'master_kelas') return 'Nama/Kode Kelas (Misal: 10.A)';
                    if(this.currentTab === 'master_mapel') return 'Nama Mata Pelajaran';
                    if(this.currentTab === 'master_siswa') return 'NIS / NISN';
                    return 'Informasi Utama';
                },
                getCol2Label() {
                    if(this.currentTab === 'master_guru') return 'Mata Pelajaran yang Diampu';
                    if(this.currentTab === 'master_kelas') return 'Tingkat (Misal: Tingkat X)';
                    if(this.currentTab === 'master_mapel') return 'Kode Singkatan (Misal: MUM)';
                    if(this.currentTab === 'master_siswa') return 'Nama Lengkap Siswa';
                    return 'Detail Tambahan';
                },
                getModalTitle() {
                    if(this.currentTab === 'user') return 'Data User';
                    if(this.currentTab === 'master_guru') return 'Data Guru';
                    if(this.currentTab === 'master_kelas') return 'Data Kelas';
                    if(this.currentTab === 'master_mapel') return 'Data Mapel';
                    if(this.currentTab === 'master_siswa') return 'Data Siswa';
                    if(this.currentTab === 'jadwal') return 'Jadwal Pelajaran';
                    if(this.modalMode === 'koreksi_siswa') return 'Koreksi Absensi Siswa';
                    return 'Data';
                },
                confirmDelete(item) {
                    if(this.currentTab === 'user' && item.username === 'admin') {
                        this.showToast('Ditolak', 'Akun admin utama tidak bisa dihapus.', 'error');
                        return;
                    }
                    this.itemToDelete = item; this.showDeleteModal = true;
                },
                async deleteItem() {
                    if(!this.itemToDelete) return;
                    let item = this.itemToDelete; let tableName = ""; let localKey = "";
                    if(this.currentTab === 'master_guru') { tableName = 'TEACHERS'; localKey = 'TEACHERS'; this.masterGuru = this.masterGuru.filter(i => String(i.id) !== String(item.id)); }
                    if(this.currentTab === 'master_kelas') { tableName = 'CLASSES'; localKey = 'CLASSES'; this.masterKelas = this.masterKelas.filter(i => String(i.id) !== String(item.id)); }
                    if(this.currentTab === 'master_mapel') { tableName = 'SUBJECTS'; localKey = 'SUBJECTS'; this.masterMapel = this.masterMapel.filter(i => String(i.id) !== String(item.id)); }
                    if(this.currentTab === 'master_siswa') { tableName = 'STUDENTS'; localKey = 'STUDENTS'; this.masterSiswa = this.masterSiswa.filter(i => String(i.id) !== String(item.id)); }
                    if(this.currentTab === 'user') { tableName = 'USERS'; localKey = 'USERS'; this.masterUser = this.masterUser.filter(i => String(i.id) !== String(item.id)); }
                    if(this.currentTab === 'jadwal') { tableName = 'SCHEDULES'; localKey = 'SCHEDULES'; this.uploadedSchedules = this.uploadedSchedules.filter(i => String(i.id) !== String(item.id)); }
                    try {
                        this.saveToLocal(localKey, this.getActiveDataList());
                        await window.fb.deleteDoc(window.fb.doc(window.db, tableName, item.id));
                        this.showToast('Dihapus', 'Data telah dihapus dari database.', 'success');
                    } catch (error) {
                        this.showToast('Peringatan', 'Terhapus secara lokal.', 'warning');
                    }
                    this.showDeleteModal = false; this.itemToDelete = null;
                },
                promptDeleteAll() { this.showDeleteAllModal = true; },
                async executeDeleteAll() {
                    let tableName = ""; let localKey = ""; let newData = [];
                    if(this.currentTab === 'master_guru') { this.masterGuru = []; tableName = 'TEACHERS'; localKey = 'TEACHERS'; newData = this.masterGuru; }
                    if(this.currentTab === 'master_kelas') { this.masterKelas = []; tableName = 'CLASSES'; localKey = 'CLASSES'; newData = this.masterKelas; }
                    if(this.currentTab === 'master_mapel') { this.masterMapel = []; tableName = 'SUBJECTS'; localKey = 'SUBJECTS'; newData = this.masterMapel; }
                    if(this.currentTab === 'master_siswa') { this.masterSiswa = []; tableName = 'STUDENTS'; localKey = 'STUDENTS'; newData = this.masterSiswa; }
                    if(this.currentTab === 'jadwal') { this.uploadedSchedules = []; tableName = 'SCHEDULES'; localKey = 'SCHEDULES'; newData = this.uploadedSchedules; }
                    if(this.currentTab === 'user') { this.masterUser = this.masterUser.filter(u => u.username === 'admin'); tableName = 'USERS'; localKey = 'USERS'; newData = this.masterUser; }
                    
                    this.saveToLocal(localKey, newData);
                    try {
                        const payload = JSON.parse(JSON.stringify(newData));
                        await this.firestoreSaveDbTable(tableName, payload);
                        this.showToast('Berhasil', 'Seluruh data berhasil dibersihkan di database.', 'success');
                    } catch (error) {
                        this.showToast('Peringatan', 'Data dihapus secara lokal.', 'warning');
                    }
                    this.showDeleteAllModal = false;
                },
                handleFileImport(event) {
                    const tab = this.currentTab; const file = event.target.files[0]; if(!file) return;
                    let tableName = ""; let localKey = "";
                    if(tab === 'master_guru') { tableName = 'TEACHERS'; localKey = 'TEACHERS'; }
                    if(tab === 'master_kelas') { tableName = 'CLASSES'; localKey = 'CLASSES'; }
                    if(tab === 'master_mapel') { tableName = 'SUBJECTS'; localKey = 'SUBJECTS'; }
                    if(tab === 'master_siswa') { tableName = 'STUDENTS'; localKey = 'STUDENTS'; }
                    if(tab === 'user') { tableName = 'USERS'; localKey = 'USERS'; }
                    
                    const reader = new FileReader();
                    reader.onload = async (e) => {
                        const data = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(data, {type: 'array'});
                        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                        const jsonData = XLSX.utils.sheet_to_json(firstSheet, {header: 1});
                        if (jsonData.length > 1) {
                            let extractedData = [];
                            for(let i = 1; i < jsonData.length; i++) {
                                let row = jsonData[i];
                                if(row.length > 0 && row[0]) {
                                    if(tab === 'user') {
                                        extractedData.push({
                                            id: this.generateUniqueId('usr'),
                                            nama: row[0],
                                            username: row[1] || `user_${i}`,
                                            password: row[2] ? row[2].toString() : '123456',
                                            role: row[3] ? row[3].toString().toLowerCase() : 'guru',
                                            kelas_km: (row[4] || "").trim(),
                                            status: 'AKTIF',
                                            showPassword: false
                                        });
                                    } else if(tab === 'master_siswa') {
                                        extractedData.push({
                                            id: this.generateUniqueId('sis'),
                                            col1: String(row[0]),
                                            col2: row[1] || ' ',
                                            col3: (row[2] || "").trim(),
                                            col4: row[3] || ' ',
                                            status: 'AKTIF'
                                        });
                                    } else {
                                        extractedData.push({
                                            id: this.generateUniqueId(tableName.toLowerCase()),
                                            col1: String(row[0]),
                                            col2: row[1] || ' ',
                                            status: 'AKTIF'
                                        });
                                    }
                                }
                            }
                            if (extractedData.length > 0) {
                                try {
                                    if(tab === 'master_guru') this.masterGuru.unshift(...extractedData);
                                    if(tab === 'master_kelas') this.masterKelas.unshift(...extractedData);
                                    if(tab === 'master_mapel') this.masterMapel.unshift(...extractedData);
                                    if(tab === 'master_siswa') this.masterSiswa.unshift(...extractedData);
                                    if(tab === 'user') this.masterUser.unshift(...extractedData);
                                    
                                    this.saveToLocal(localKey, this.getActiveDataList());
                                    const payload = JSON.parse(JSON.stringify(extractedData));
                                    await this.firestoreBatchSave(tableName, payload);
                                    this.showToast('Import Berhasil', `${extractedData.length} baris tersimpan di Database.`, 'success');
                                } catch (error) {
                                    this.showToast('Peringatan', 'Tersimpan lokal. Gagal sinkron ke database.', 'warning');
                                }
                            }
                        }
                    };
                    reader.readAsArrayBuffer(file); event.target.value = "";
                },
                downloadTemplate() {
                    let ws_data = []; let cols = [];
                    if (this.currentTab === 'user') {
                        ws_data = [["Nama", "Username", "Password", "Role", "Kelas (Jika KM)"], ["Budi", "guru_budi", "budi123", "guru", ""] ];
                        cols = [{wch: 30}, {wch: 25}, {wch: 25}, {wch: 20}, {wch: 20}];
                    } else if (this.currentTab === 'master_siswa') {
                        ws_data = [["NIS/NISN", "Nama Lengkap Siswa", "Kelas", "Agama"], ["123456", "Ahmad Subarjo", "10.A", "Islam"] ];
                        cols = [{wch: 15}, {wch: 35}, {wch: 15}, {wch: 15}];
                    } else {
                        ws_data = [["Informasi Utama", "Detail Tambahan"], ["Contoh Data", "Keterangan"] ];
                        cols = [{wch: 30}, {wch: 30}];
                    }
                    const ws = XLSX.utils.aoa_to_sheet(ws_data); ws['!cols'] = cols;
                    const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, "Template");
                    XLSX.writeFile(wb, `Template_Import_${this.currentTab}.xlsx`);
                },
                downloadScheduleTemplateExcel() {
                    const ws_data = [["Hari", "Jam Ke", "Waktu", "Kelas", "Mata Pelajaran", "Guru Pengampu"], ["Senin", "1", "06:30 - 07:15", "10.A", "Nama Mapel", "Nama Guru"]];
                    const cols = [{wch: 15}, {wch: 10}, {wch: 20}, {wch: 15}, {wch: 30}, {wch: 35}];
                    const ws = XLSX.utils.aoa_to_sheet(ws_data); ws['!cols'] = cols;
                    const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, "Jadwal_Pelajaran");
                    XLSX.writeFile(wb, "Template_Jadwal_Pelajaran.xlsx");
                },
                handleScheduleUpload(event) {
                    const file = event.target.files[0]; if(!file) return;
                    this.showToast('Memproses...', 'Membaca file jadwal...', 'info');
                    const reader = new FileReader();
                    reader.onload = async (e) => {
                        const data = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(data, {type: 'array'});
                        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                        const jsonData = XLSX.utils.sheet_to_json(firstSheet, {header: 1});
                        if (jsonData.length > 1) {
                            let extractedData = [];
                            for (let i = 1; i < jsonData.length; i++) {
                                let row = jsonData[i];
                                if (row.length > 0 && row[0]) {
                                    extractedData.push({
                                        id: this.generateUniqueId('sch'),
                                        hari: (row[0] || "").trim(),
                                        jamke: String(row[1] || '-'),
                                        waktu: String(row[2] || '-'),
                                        kelas: (row[3] || "").trim(),
                                        mapel: String(row[4] || '-'),
                                        guru: String(row[5] || '-')
                                    });
                                }
                            }
                            try {
                                this.uploadedSchedules.unshift(...extractedData);
                                this.saveToLocal('SCHEDULES', this.uploadedSchedules);
                                const payload = JSON.parse(JSON.stringify(extractedData));
                                await this.firestoreBatchSave('SCHEDULES', payload);
                                this.showToast('Import Berhasil', `${extractedData.length} Jadwal disimpan ke Database.`, 'success');
                            } catch (error) {
                                this.showToast('Peringatan', 'Jadwal disimpan lokal. Gagal sinkron ke database.', 'warning');
                            }
                        }
                    };
                    reader.readAsArrayBuffer(file); event.target.value = "";
                },
                showToast(title, message, type='info') {
                    const id = Date.now() + Math.random();
                    this.toasts.push({id, title, message, type});
                    setTimeout(() => this.removeToast(id), 4000);
                },
                removeToast(id) { this.toasts = this.toasts.filter(t => t.id !== id); },
                async executeTransferClass() {
                    if(!this.transferSource || !this.transferTarget) { this.showToast('Gagal', 'Silakan pilih kelas asal dan kelas tujuan.', 'error'); return; }
                    if(this.transferSource === this.transferTarget) { this.showToast('Peringatan', 'Kelas asal dan kelas tujuan tidak boleh sama.', 'warning'); return; }
                    let count = 0;
                    this.masterSiswa.forEach(siswa => {
                        if((siswa.col3 || "").trim() === this.transferSource.trim()) {
                            siswa.col3 = this.transferTarget;
                            count++;
                        }
                    });
                    if(count === 0) { this.showToast('Info', 'Tidak ada siswa yang ditemukan di kelas asal.', 'info'); return; }
                    this.saveToLocal('STUDENTS', this.masterSiswa);
                    try {
                        const payload = JSON.parse(JSON.stringify(this.masterSiswa));
                        await this.firestoreSaveDbTable('STUDENTS', payload);
                        this.showToast('Sukses', `${count} Siswa berhasil dipindahkan ke Kelas ${this.transferTarget}.`, 'success');
                    } catch (e) {
                        this.showToast('Peringatan', 'Data dipindahkan lokal. Gagal sinkron ke database.', 'warning');
                    }
                    this.showTransferModal = false; this.transferSource = ""; this.transferTarget = "";
                }
            }));
        });
    </script>
</head>
<body class="font-sans antialiased text-gray-800" x-data="appData()" x-cloak>
    <div id="animation-container" class="fixed inset-0 pointer-events-none z-0 overflow-hidden"></div>
    
    <!-- TAMPILAN LOGIN -->
    <div x-show="!isLoggedIn" class="min-h-screen flex items-center justify-center relative z-10 overflow-hidden px-4">
        <div class="login-card-bg p-6 sm:p-10 rounded-3xl w-full max-w-md z-10 relative overflow-hidden">
            <div class="mb-4">
                <div x-show="dbStatus === 'connected'" class="flex items-center justify-between p-2.5 bg-green-500/20 border border-green-500/40 rounded-xl text-xs font-bold text-green-900 backdrop-blur-md">
                    <span class="flex items-center gap-1.5 truncate">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse inline-block"></span>
                        <span class="truncate" x-text="`Database Terhubung: ${dbInfo.name}`"></span>
                    </span>
                    <button @click="syncWithDatabase(true)" title="Refresh" class="text-green-800 hover:text-green-950 p-1">
                        <i class="fa-solid fa-arrows-rotate" :class="{'fa-spin': isSyncing}"></i>
                    </button>
                </div>
                <div x-show="dbStatus === 'connecting'" class="flex items-center justify-center gap-2 p-2.5 bg-blue-500/20 border border-blue-500/40 rounded-xl text-xs font-bold text-blue-900 backdrop-blur-md">
                    <i class="fa-solid fa-circle-notch fa-spin text-blue-600"></i>
                    <span>Menghubungkan ke Cloud Firestore...</span>
                </div>
                <div x-show="dbStatus === 'error'" class="p-3 bg-red-500/20 border border-red-500/40 rounded-xl text-xs font-bold text-red-900 backdrop-blur-md">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation text-red-600"></i> Belum Terkoneksi Database</span>
                        <button @click="syncWithDatabase(true)" class="underline text-red-800">Coba Lagi</button>
                    </div>
                    <p class="text-[11px] font-normal mt-1 text-red-800">Pastikan Cloud Firestore API aktif di Google Cloud Console.</p>
                </div>
            </div>
            
            <div class="text-center mb-8 relative z-10">
                <img src="https://cdn.phototourl.com/free/2026-08-28-7d88fc6f-7c50-405f-b5e8-f8e00873e2b5.png" alt="Logo SMAN 2 Cikut" class="w-28 h-28 mx-auto mb-6 animated-logo drop-shadow-2xl">
                <div class="w-full overflow-visible">
                    <h1 class="text-4xl font-black animated-title-login tracking-wider text-gray-800">SMAN 2 CIKARANG UTARA</h1>
                    <br>
                    <p class="text-[15px] mt-2 animated-subtitle-login text-gray-700">Sistem Monitoring Kehadiran</p>
                </div>
            </div>
            
            <form @submit.prevent="login()" class="relative z-10">
                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-800 mb-2 drop-shadow-sm">Username</label>
                    <input type="text" x-model="loginForm.username" class="w-full px-4 py-3 rounded-xl border border-white/50 bg-white/40 backdrop-blur-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition outline-none font-medium placeholder-gray-500 text-gray-900" placeholder="Masukkan username" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-800 mb-2 drop-shadow-sm">Password</label>
                    <input type="password" x-model="loginForm.password" class="w-full px-4 py-3 rounded-xl border border-white/50 bg-white/40 backdrop-blur-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition outline-none font-medium placeholder-gray-500 text-gray-900" placeholder="Masukkan password" required>
                </div>
                <button type="submit" :disabled="isLoading" class="w-full bg-gradient-to-r from-blue-600/90 to-purple-600/90 backdrop-blur-md hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg flex justify-center items-center transform hover:scale-[1.02] border border-white/30">
                    <span x-show="!isLoading">Login ke Sistem</span>
                    <i x-show="isLoading" class="fa-solid fa-circle-notch fa-spin"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN DASHBOARD -->
    <div x-show="isLoggedIn" class="flex h-screen overflow-hidden relative z-10">
        <div x-show="isSidebarOpen" @click="isSidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 md:hidden" x-cloak></div>
        
        <!-- Sidebar -->
        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white/20 backdrop-blur-2xl border-r border-white/40 text-gray-800 flex flex-col flex-shrink-0 shadow-[4px_0_24px_rgba(0,0,0,0.05)] no-print transition-transform duration-300 ease-in-out md:translate-x-0">
            <div class="h-20 flex items-center px-6 border-b border-white/30 bg-white/10 backdrop-blur-md text-gray-800">
                <img src="https://cdn.phototourl.com/free/2026-08-28-7d88fc6f-7c50-405f-b5e8-f8e00873e2b5.png" alt="Logo Graduation" class="w-8 h-8 mr-3 drop-shadow-sm" style="filter: hue-rotate(270deg) brightness(1.2);">
                <div>
                    <h1 class="text-xl font-black leading-tight tracking-wider drop-shadow-sm">SMAN 2</h1>
                    <p class="text-xs text-gray-600 font-bold">CIKARANG UTARA</p>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto py-4 scrollbar-thin">
                <template x-if="currentUser.role === 'admin'">
                    <nav class="space-y-1 px-3">
                        <p class="px-3 text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 mt-2 drop-shadow-sm">Monitoring & Laporan</p>
                        <a href="#" @click.prevent="currentTab = 'dashboard'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'dashboard', 'hover:bg-white/30 text-gray-700': currentTab !== 'dashboard'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-chart-pie w-6" :class="{'text-purple-600': currentTab === 'dashboard'}"></i> Dashboard Realtime
                        </a>
                        <a href="#" @click.prevent="currentTab = 'monitoring_guru'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'monitoring_guru', 'hover:bg-white/30 text-gray-700': currentTab !== 'monitoring_guru'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-chalkboard-user w-6" :class="{'text-purple-600': currentTab === 'monitoring_guru'}"></i> Monitoring Guru
                        </a>
                        <a href="#" @click.prevent="currentTab = 'laporan'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'laporan', 'hover:bg-white/30 text-gray-700': currentTab !== 'laporan'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-file-invoice w-6" :class="{'text-purple-600': currentTab === 'laporan'}"></i> Laporan Guru
                        </a>
                        <a href="#" @click.prevent="currentTab = 'laporan_siswa'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'laporan_siswa', 'hover:bg-white/30 text-gray-700': currentTab !== 'laporan_siswa'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-clipboard-user w-6" :class="{'text-purple-600': currentTab === 'laporan_siswa'}"></i> Laporan Siswa
                        </a>
                        <a href="#" @click.prevent="currentTab = 'koreksi_libur'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'koreksi_libur', 'hover:bg-white/30 text-gray-700': currentTab !== 'koreksi_libur'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition mt-1">
                            <i class="fa-solid fa-calendar-xmark w-6" :class="{'text-purple-600': currentTab === 'koreksi_libur'}"></i> Koreksi & Libur
                        </a>

                        <p class="px-3 text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 mt-6 drop-shadow-sm">Master Data</p>
                        <a href="#" @click.prevent="currentTab = 'master_guru'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'master_guru', 'hover:bg-white/30 text-gray-700': currentTab !== 'master_guru'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-users w-6" :class="{'text-purple-600': currentTab === 'master_guru'}"></i> Master Guru
                        </a>
                        <a href="#" @click.prevent="currentTab = 'master_kelas'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'master_kelas', 'hover:bg-white/30 text-gray-700': currentTab !== 'master_kelas'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-door-open w-6" :class="{'text-purple-600': currentTab === 'master_kelas'}"></i> Master Kelas
                        </a>
                        <a href="#" @click.prevent="currentTab = 'master_mapel'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'master_mapel', 'hover:bg-white/30 text-gray-700': currentTab !== 'master_mapel'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-book w-6" :class="{'text-purple-600': currentTab === 'master_mapel'}"></i> Master Mapel
                        </a>
                        <a href="#" @click.prevent="currentTab = 'master_siswa'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'master_siswa', 'hover:bg-white/30 text-gray-700': currentTab !== 'master_siswa'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-user-graduate w-6" :class="{'text-purple-600': currentTab === 'master_siswa'}"></i> Master Siswa
                        </a>
                        <a href="#" @click.prevent="currentTab = 'jadwal'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'jadwal', 'hover:bg-white/30 text-gray-700': currentTab !== 'jadwal'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-regular fa-calendar-days w-6" :class="{'text-purple-600': currentTab === 'jadwal'}"></i> Jadwal Pelajaran
                        </a>

                        <p class="px-3 text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 mt-6 drop-shadow-sm">Sistem</p>
                        <a href="#" @click.prevent="currentTab = 'user'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'user', 'hover:bg-white/30 text-gray-700': currentTab !== 'user'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-user-shield w-6" :class="{'text-purple-600': currentTab === 'user'}"></i> Manajemen User
                        </a>
                    </nav>
                </template>

                <template x-if="currentUser.role === 'piket'">
                    <nav class="space-y-1 px-3 mt-4">
                        <p class="px-3 text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 mt-2 drop-shadow-sm">Menu Piket</p>
                        <a href="#" @click.prevent="currentTab = 'dashboard'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'dashboard', 'hover:bg-white/30 text-gray-700': currentTab !== 'dashboard'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-chart-pie w-6" :class="{'text-purple-600': currentTab === 'dashboard'}"></i> Dashboard Realtime
                        </a>
                        <a href="#" @click.prevent="currentTab = 'monitoring_guru'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'monitoring_guru', 'hover:bg-white/30 text-gray-700': currentTab !== 'monitoring_guru'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-chalkboard-user w-6" :class="{'text-purple-600': currentTab === 'monitoring_guru'}"></i> Monitoring Guru
                        </a>
                    </nav>
                </template>

                <template x-if="currentUser.role === 'guru'">
                    <nav class="space-y-1 px-3 mt-4">
                        <p class="px-3 text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 mt-2 drop-shadow-sm">Jadwal & Absen Saya</p>
                        <a href="#" @click.prevent="currentTab = 'dashboard'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'dashboard', 'hover:bg-white/30 text-gray-700': currentTab !== 'dashboard'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-house w-6" :class="{'text-purple-600': currentTab === 'dashboard'}"></i> Beranda
                        </a>
                        <a href="#" @click.prevent="currentTab = 'jadwal_saya'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'jadwal_saya', 'hover:bg-white/30 text-gray-700': currentTab !== 'jadwal_saya'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-calendar-check w-6" :class="{'text-purple-600': currentTab === 'jadwal_saya'}"></i> Semua Jadwal
                        </a>

                        <p class="px-3 text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 mt-6 drop-shadow-sm">Absensi Murid</p>
                        <a href="#" @click.prevent="currentTab = 'guru_absensi_siswa'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'guru_absensi_siswa', 'hover:bg-white/30 text-gray-700': currentTab !== 'guru_absensi_siswa'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-user-check w-6" :class="{'text-purple-600': currentTab === 'guru_absensi_siswa'}"></i> Absensi Siswa
                        </a>
                        <a href="#" @click.prevent="currentTab = 'guru_laporan_siswa'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'guru_laporan_siswa', 'hover:bg-white/30 text-gray-700': currentTab !== 'guru_laporan_siswa'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-file-lines w-6" :class="{'text-purple-600': currentTab === 'guru_laporan_siswa'}"></i> Laporan Absensi
                        </a>
                    </nav>
                </template>

                <template x-if="currentUser.role === 'kmkelas'">
                    <nav class="space-y-1 px-3 mt-4">
                        <a href="#" @click.prevent="currentTab = 'km_dashboard'; isSidebarOpen = false" :class="{'bg-white/50 border border-white/60 shadow-sm text-purple-800': currentTab === 'km_dashboard', 'hover:bg-white/30 text-gray-700': currentTab !== 'km_dashboard'}" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition">
                            <i class="fa-solid fa-clipboard-check w-6" :class="{'text-purple-600': currentTab === 'km_dashboard'}"></i> Absensi Kelas
                        </a>
                    </nav>
                </template>
            </div>

            <div class="p-4 bg-white/20 backdrop-blur-md border-t border-white/30">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-pink-400 to-purple-500 border border-white/60 flex items-center justify-center text-white font-bold mr-3 shadow-md">
                        <span x-text="(currentUser.nama || 'U').charAt(0)"></span>
                    </div>
                    <div class="overflow-hidden text-sm">
                        <p class="font-black truncate text-gray-900 drop-shadow-sm" x-text="currentUser.nama"></p>
                        <p class="text-purple-700 text-[10px] font-bold uppercase drop-shadow-sm">
                            <span x-text="currentUser.role"></span>
                            <span x-show="currentUser.role === 'kmkelas'" x-text="` - KELAS ${currentUser.kelas_km}`"></span>
                        </p>
                    </div>
                </div>
                <button x-show="['guru', 'kmkelas', 'piket'].includes(currentUser.role)" @click="showPasswordModal = true; passwordForm = { oldPassword: '', newPassword: '', confirmPassword: ''}" class="w-full flex justify-center items-center py-2 px-4 mb-2 bg-white/40 backdrop-blur-sm border border-white/60 rounded-xl text-sm font-bold text-gray-800 hover:bg-purple-500/80 hover:text-white hover:border-purple-400 transition shadow-sm">
                    <i class="fa-solid fa-key mr-2"></i> Ganti Password
                </button>
                <button @click="logout()" class="w-full flex justify-center items-center py-2 px-4 bg-white/40 backdrop-blur-sm border border-white/60 rounded-xl text-sm font-bold text-gray-800 hover:bg-red-500/80 hover:text-white hover:border-red-400 transition shadow-sm">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto relative scrollbar-thin w-full">
            <header class="bg-white/30 backdrop-blur-2xl shadow-sm border-b border-white/50 sticky top-0 z-30 px-4 sm:px-6 py-4 flex justify-between items-center no-print">
                <div class="flex items-center gap-3">
                    <button @click="isSidebarOpen = true" class="md:hidden text-gray-800 hover:text-purple-700 transition p-2 bg-white/40 rounded-xl backdrop-blur-sm border border-white/60 shadow-sm">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900 drop-shadow-sm line-clamp-1" x-text="getTabTitle()"></h2>
                        <p class="text-xs sm:text-sm font-bold text-gray-700 drop-shadow-sm" x-text="waktuSekarang"></p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl border backdrop-blur-md text-xs font-bold cursor-pointer transition hover:opacity-80"
                        @click="if(dbStatus === 'error') showDbErrorDetail()"
                        :title="dbStatus === 'error' ? (dbInfo.message || 'Klik untuk melihat detail error') : dbInfo.name"
                        :class="dbStatus === 'connected' ? 'bg-green-500/20 border-green-400 text-green-900' : (dbStatus === 'connecting' ? 'bg-blue-500/20 border-blue-400 text-blue-900' : 'bg-red-500/20 border-red-400 text-red-900')">
                        <span class="w-2.5 h-2.5 rounded-full" :class="dbStatus === 'connected' ? 'bg-green-500 animate-pulse' : (dbStatus === 'connecting' ? 'bg-blue-500 animate-spin' : 'bg-red-500')"></span>
                        <span x-text="dbStatus === 'connected' ? `DB: ${dbInfo.name}` : (dbStatus === 'connecting' ? 'Menghubungkan...' : 'DB Terputus')"></span>
                    </div>
                    <button @click="syncWithDatabase(true)" title="Sinkronkan Data Server" :disabled="isSyncing" class="px-3 py-2 bg-white/40 hover:bg-white/70 border border-white/60 rounded-xl font-bold text-xs text-purple-900 transition flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-rotate" :class="{'fa-spin': isSyncing}"></i>
                        <span class="hidden sm:inline">Refresh Data</span>
                    </button>
                </div>
            </header>

            <div class="p-4 sm:p-6 pb-24">
                
                <!-- BANNER PENIADAAN ABSENSI (HARI LIBUR) -->
                <div x-show="todayHoliday && ['dashboard', 'km_dashboard', 'guru_absensi_siswa'].includes(currentTab)" class="bg-blue-100/90 backdrop-blur-md border border-blue-300 text-blue-900 p-4 sm:p-5 rounded-2xl mb-6 shadow-sm flex items-start gap-4">
                    <div class="mt-1">
                        <i class="fa-solid fa-bullhorn text-2xl sm:text-3xl text-blue-600 drop-shadow-sm"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-lg sm:text-xl drop-shadow-sm">Peniadaan Absensi Hari Ini</h4>
                        <p class="font-bold text-sm text-blue-800 mt-1">Hari ini (<span x-text="todayHoliday?.tanggal"></span>) ditetapkan sebagai hari libur atau peniadaan absensi karena: <span class="font-black text-red-900 uppercase underline decoration-red-600" x-text="todayHoliday?.keterangan"></span>.</p>
                        <p class="text-xs font-bold text-blue-700 mt-2"><i class="fa-solid fa-circle-check mr-1"></i>Sistem Auto-Alpa otomatis dinonaktifkan untuk hari ini.</p>
                    </div>
                </div>

                <!-- TAB: DASHBOARD (ADMIN, PIKET & GURU) -->
                <div x-show="currentTab === 'dashboard'" x-cloak>
                    <div x-show="['admin', 'piket'].includes(currentUser.role)">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="glass-panel p-5 rounded-2xl border-b-4 border-b-blue-500">
                                <p class="text-xs sm:text-sm text-gray-700 font-bold mb-1 drop-shadow-sm">KELAS BERLANGSUNG</p>
                                <p class="text-3xl font-black text-gray-900" x-text="getRealtimeDashboard().length"></p>
                            </div>
                            <div class="glass-panel p-5 rounded-2xl border-b-4 border-b-green-500">
                                <p class="text-xs sm:text-sm text-gray-700 font-bold mb-1 drop-shadow-sm">GURU HADIR</p>
                                <p class="text-3xl font-black text-gray-900" x-text="getRealtimeDashboard().filter(s => s.status === 'HADIR').length"></p>
                            </div>
                            <div class="glass-panel p-5 rounded-2xl border-b-4 border-b-yellow-500">
                                <p class="text-xs sm:text-sm text-gray-700 font-bold mb-1 drop-shadow-sm">MEMBERI TUGAS</p>
                                <p class="text-3xl font-black text-gray-900" x-text="getRealtimeDashboard().filter(s => s.status === 'TUGAS').length"></p>
                            </div>
                            <div class="glass-panel p-5 rounded-2xl border-b-4 border-b-red-500">
                                <p class="text-xs sm:text-sm text-gray-700 font-bold mb-1 drop-shadow-sm">BELUM INFO / ALPA</p>
                                <p class="text-3xl font-black text-gray-900" x-text="getRealtimeDashboard().filter(s => s.status === 'BELUM' || s.status === 'ALPA').length"></p>
                            </div>
                        </div>

                        <div class="glass-panel p-6 rounded-3xl">
                            <h3 class="font-black text-gray-900 mb-4 text-xl border-b border-white/40 pb-3 drop-shadow-sm"><i class="fa-solid fa-satellite-dish text-purple-600 mr-2"></i>Kondisi Kelas Realtime Saat Ini</h3>
                            
                            <div x-show="getRealtimeDashboard().length === 0" class="text-center py-10">
                                <i class="fa-solid fa-mug-hot text-5xl text-gray-400 mb-4"></i>
                                <p class="text-gray-700 font-bold">Saat ini tidak ada kelas yang sedang berlangsung sesuai jadwal pelajaran.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                <template x-for="kelas in getRealtimeDashboard()" :key="kelas.id">
                                    <div class="glass-card p-4 rounded-xl flex flex-col relative transition-all shadow-md border-2"
                                        :class="{'border-green-500 bg-green-100/90': kelas.status === 'HADIR', 'border-red-500 bg-red-100/90': kelas.status === 'BELUM' || kelas.status === 'ALPA', 'border-yellow-500 bg-yellow-100/90': kelas.status === 'TUGAS'}">
                                        
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-lg font-black text-gray-900 drop-shadow-sm" x-text="kelas.kelas"></span>
                                            <span @click="if(kelas.status === 'TUGAS') lihatTugas(kelas)"
                                                class="px-2 py-1 text-[10px] font-black rounded-md shadow-sm border border-white/50"
                                                :class="{
                                                    'bg-green-500 text-white': kelas.status === 'HADIR',
                                                    'bg-red-500 text-white': kelas.status === 'BELUM' || kelas.status === 'ALPA',
                                                    'bg-yellow-500 text-white cursor-pointer hover:bg-yellow-600 transition': kelas.status === 'TUGAS'
                                                }">
                                                <span x-text="kelas.status === 'BELUM' ? 'BELUM ADA INFO' : kelas.status"></span>
                                                <i x-show="kelas.status === 'TUGAS'" class="fa-solid fa-eye ml-1"></i>
                                            </span>
                                        </div>
                                        
                                        <p class="font-bold text-sm text-gray-900 truncate" x-text="kelas.mapel"></p>
                                        <p class="text-xs text-gray-700 mb-3 font-bold truncate" x-text="kelas.guru"></p>
                                        
                                        <div class="mt-auto pt-2 border-t border-black/10 text-xs font-bold text-gray-800 mb-4">
                                            Jam Ke-<span x-text="kelas.jamKe"></span> (<span x-text="kelas.waktu"></span>)
                                        </div>
                                        
                                        <div x-show="currentUser.role === 'admin'" class="flex gap-1 w-full mt-auto">
                                            <button @click="markAttendance(kelas.id, 'HADIR')" class="flex-1 py-2 text-[10px] font-black rounded-lg transition backdrop-blur-sm" :class="kelas.status === 'HADIR' ? 'bg-green-600 text-white shadow-md border border-green-500' : 'bg-white/50 text-green-700 border border-green-400 hover:bg-green-100/80'">HADIR</button>
                                            <button @click="markAttendance(kelas.id, 'TUGAS')" class="flex-1 py-2 text-[10px] font-black rounded-lg transition backdrop-blur-sm" :class="kelas.status === 'TUGAS' ? 'bg-yellow-500 text-white shadow-md border border-yellow-400' : 'bg-white/50 text-yellow-700 border border-yellow-400 hover:bg-yellow-100/80'">TUGAS</button>
                                            <button @click="markAttendance(kelas.id, 'ALPA')" class="flex-1 py-2 text-[10px] font-black rounded-lg transition backdrop-blur-sm" :class="kelas.status === 'ALPA' ? 'bg-red-600 text-white shadow-md border border-red-500' : 'bg-white/50 text-red-700 border border-red-400 hover:bg-red-100/80'">ALPA</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- View Guru -->
                    <div x-show="currentUser.role === 'guru'">
                        <div class="glass-panel p-8 rounded-3xl mb-6 relative overflow-hidden border-2 border-white/60">
                            <div class="relative z-10">
                                <h2 class="text-3xl font-black mb-2 text-gray-900 drop-shadow-md">Assalamu'alaikum... <span x-text="currentUser.nama"></span>!</h2>
                                <p class="text-gray-800 font-bold text-lg drop-shadow-sm">Berikut jadwal mengajar Anda hari ini. Tombol konfirmasi kehadiran dapat dikonfirmasi sampai <span class="text-red-600 font-black">jam 16:00 WIB</span>.</p>
                                <div class="mt-4">
                                    <span class="inline-flex items-center px-6 py-3 bg-purple-100/90 border border-purple-300 rounded-2xl shadow-md text-purple-900 font-black text-2xl backdrop-blur-sm transition hover:bg-purple-200">
                                        <i class="fa-solid fa-clock mr-3"></i> Total mengajar&nbsp;
                                        <span x-text="uploadedSchedules.filter(s => s.guru && (s.guru.toLowerCase().trim().includes((currentUser.nama || '').toLowerCase().trim()) || (currentUser.nama || '').toLowerCase().trim().includes(s.guru.toLowerCase().trim()))).length"></span>&nbsp;JP
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="glass-panel p-6 rounded-3xl">
                            <template x-for="group in getGroupedTodayGuruSchedules()" :key="group.kelas">
                                <div class="glass-card rounded-2xl p-5 mb-4 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4 transition hover:shadow-md border border-white/60">
                                    <div class="text-center md:text-left w-full md:w-auto">
                                        <h3 class="text-2xl font-black text-gray-900 mb-1 drop-shadow-sm" x-text="`Kelas: ${group.kelas}`"></h3>
                                        <p class="text-gray-800 font-bold text-sm mb-1">Mata Pelajaran: <span class="text-purple-900 font-black" x-text="group.mapels.join(', ')"></span></p>
                                        <p class="text-gray-700 text-xs font-bold" x-text="`Jam Ke-${group.jamKeList.join(', ')} (${group.waktuMulai})`"></p>
                                    </div>
                                    
                                    <div x-show="!hasGroupAttended(group.jadwalIds)" class="w-full md:w-auto mt-4 md:mt-0">
                                        <template x-if="!isGroupSchedulePassed(group.waktuMulai)">
                                            <div class="flex gap-3 w-full md:w-auto">
                                                <button x-show="isTimePastOrOngoing(group.waktuMulai)" @click="markGroupAttendance(group.jadwalIds, 'HADIR')" class="flex-1 md:flex-none px-6 py-3 bg-green-500/90 backdrop-blur border border-green-400 hover:bg-green-600 text-white font-black rounded-xl shadow-lg transition transform hover:-translate-y-1">
                                                    <i class="fa-solid fa-check-circle mr-2"></i> MASUK KELAS
                                                </button>
                                                <button @click="openTugasModal(group.jadwalIds)" class="flex-1 md:flex-none px-6 py-3 bg-yellow-500/90 backdrop-blur border border-yellow-400 hover:bg-yellow-600 text-white font-black rounded-xl shadow-lg transition transform hover:-translate-y-1">
                                                    <i class="fa-solid fa-file-pen mr-2"></i> BERI TUGAS
                                                </button>
                                            </div>
                                        </template>
                                        <template x-if="isGroupSchedulePassed(group.waktuMulai)">
                                            <div class="text-center w-full md:w-auto">
                                                <span class="px-6 py-3 rounded-xl bg-red-100/90 border border-red-300 text-red-700 font-black inline-block w-full backdrop-blur-md shadow-inner">
                                                    <i class="fa-solid fa-lock mr-2"></i> Waktu Konfirmasi Habis (Lewat Jam 16:00)
                                                </span>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <div x-show="hasGroupAttended(group.jadwalIds)" class="w-full md:w-auto mt-4 md:mt-0 text-center">
                                        <span class="px-6 py-3 rounded-xl text-white font-black shadow inline-block w-full md:w-auto backdrop-blur-md border border-white/50"
                                            :class="{
                                                'bg-green-500/90': getGroupAttendanceStatus(group.jadwalIds) === 'HADIR',
                                                'bg-yellow-500/90': getGroupAttendanceStatus(group.jadwalIds) === 'TUGAS',
                                                'bg-red-500/90': getGroupAttendanceStatus(group.jadwalIds) === 'ALPA'
                                            }">
                                            <i class="fa-solid fa-thumbs-up mr-2"></i> STATUS: <span x-text="getGroupAttendanceStatus(group.jadwalIds)"></span>
                                        </span>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="getGroupedTodayGuruSchedules().length === 0">
                                <div class="py-12 text-center">
                                    <i class="fa-solid fa-mug-hot text-5xl mb-4 text-purple-600 drop-shadow-md"></i>
                                    <h3 class="text-2xl font-black text-gray-900 drop-shadow-sm">Tidak ada jadwal pada hari ini.</h3>
                                    <p class="text-gray-700 mt-2 font-bold">Anda dapat beristirahat atau mengerjakan administrasi lainnya.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- TAB: MONITORING GURU -->
                <div x-show="currentTab === 'monitoring_guru'" x-cloak>
                    <div class="glass-panel p-6 rounded-3xl mb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-black text-gray-900 text-xl flex items-center drop-shadow-sm">
                                <i class="fa-solid fa-chalkboard-user text-purple-600 mr-3"></i> Monitoring Kehadiran Guru Hari Ini
                            </h3>
                        </div>
                        
                        <div class="bg-white/40 p-4 rounded-xl border border-white/60 mb-6 flex flex-col sm:flex-row gap-4 no-print shadow-sm">
                            <div class="flex-1">
                                <label class="block text-[10px] font-black text-gray-600 mb-1 uppercase tracking-wider">Cari Spesifik Berdasarkan</label>
                                <select x-model="searchMonitoringGuru" @change="searchMonitoringKelas = ''" class="w-full px-4 py-2 border border-white/60 rounded-xl bg-white/70 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm transition shadow-sm">
                                    <option value="">-- Nama Guru (Pilih Semua) --</option>
                                    <template x-for="g in masterGuru" :key="g.id">
                                        <option :value="g.col1" x-text="g.col1"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="flex items-center justify-center font-bold text-gray-400 px-2 sm:pt-5">Atau</div>
                            <div class="flex-1">
                                <label class="block text-[10px] font-black text-gray-600 mb-1 uppercase tracking-wider">Cari Spesifik Berdasarkan</label>
                                <select x-model="searchMonitoringKelas" @change="searchMonitoringGuru = ''" class="w-full px-4 py-2 border border-white/60 rounded-xl bg-white/70 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm transition shadow-sm">
                                    <option value="">-- Kelas (Pilih Semua) --</option>
                                    <template x-for="k in masterKelas" :key="k.id">
                                        <option :value="k.col1" x-text="k.col1"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="flex-none flex items-end">
                                <button @click="searchMonitoringGuru = ''; searchMonitoringKelas = '';" class="h-[40px] px-5 py-2 bg-gray-100 text-gray-700 text-sm font-bold rounded-xl shadow-sm transition hover:bg-gray-200 border border-gray-300 flex items-center">
                                    <i class="fa-solid fa-rotate-left mr-2"></i> Reset Filter
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto bg-white/30 backdrop-blur-md rounded-xl border border-white/50 shadow-sm">
                            <table class="min-w-full text-sm border-collapse">
                                <thead>
                                    <tr>
                                        <th @click="sortByMonitoring('jam_waktu')" class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60 cursor-pointer hover:bg-white/60 transition">Waktu <i class="fa-solid fa-sort ml-1 text-gray-400"></i></th>
                                        <th @click="sortByMonitoring('guru')" class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60 cursor-pointer hover:bg-white/60 transition">Nama Guru <i class="fa-solid fa-sort ml-1 text-gray-400"></i></th>
                                        <th @click="sortByMonitoring('kelas_mapel')" class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60 cursor-pointer hover:bg-white/60 transition">Kelas & Mapel <i class="fa-solid fa-sort ml-1 text-gray-400"></i></th>
                                        <th @click="sortByMonitoring('status')" class="px-4 py-3 text-center font-black text-gray-900 uppercase bg-white/40 border-b border-white/60 cursor-pointer hover:bg-white/60 transition">Status <i class="fa-solid fa-sort ml-1 text-gray-400"></i></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/40 bg-white/20">
                                    <template x-for="row in getTodayMonitoring()" :key="row.id">
                                        <tr class="hover:bg-white/40 transition">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="font-black text-gray-900" x-text="`Jam Ke-${row.jamke}`"></span><br>
                                                <span class="text-xs font-bold text-gray-700" x-text="row.waktu"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap font-black text-gray-900 drop-shadow-sm" x-text="row.guru"></td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 py-1 bg-white/70 border border-white/80 text-purple-900 rounded font-black text-xs shadow-sm" x-text="row.kelas"></span><br>
                                                <span class="text-xs font-bold text-gray-800 mt-1 block" x-text="row.mapel"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <span @click="if(row.status === 'TUGAS') lihatTugas(row)"
                                                    class="px-3 py-1.5 text-xs font-black rounded-lg shadow-sm border border-white/50 inline-block text-center w-32"
                                                    :class="{
                                                        'bg-green-500 text-white': row.status === 'HADIR',
                                                        'bg-yellow-500 text-white cursor-pointer hover:bg-yellow-600 transition': row.status === 'TUGAS',
                                                        'bg-red-500 text-white': row.status === 'ALPA',
                                                        'bg-gray-200 text-gray-600': row.status === 'BELUM' || row.status === 'BELUM ADA INFO'
                                                    }">
                                                    <span x-text="row.status === 'BELUM' ? 'BELUM ADA INFO' : row.status"></span>
                                                    <i x-show="row.status === 'TUGAS'" class="fa-solid fa-eye ml-1"></i>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="getTodayMonitoring().length === 0">
                                        <td colspan="4" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-500">
                                                <i class="fa-solid fa-clipboard-question text-4xl mb-3 text-gray-400 drop-shadow-sm"></i>
                                                <span class="font-bold text-gray-700">Tidak ada jadwal pelajaran hari ini yang sesuai kriteria pencarian.</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB: LAPORAN GURU -->
                <div x-show="currentTab === 'laporan'" x-cloak>
                    <div class="glass-panel p-6 rounded-3xl mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 no-print">
                            <h3 class="font-black text-gray-900 text-xl flex items-center drop-shadow-sm">
                                <i class="fa-solid fa-file-invoice text-purple-600 mr-3"></i> Laporan Kehadiran Guru
                            </h3>
                            <div class="flex gap-2">
                                <button @click="printPDF()" class="px-4 py-2 bg-red-400/90 text-white text-sm font-bold rounded-lg shadow transition hover:bg-red-500 border border-red-300 flex items-center">
                                    <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
                                </button>
                                <button @click="downloadLaporanExcel()" class="px-4 py-2 bg-green-500/90 text-white text-sm font-bold rounded-lg shadow transition hover:bg-green-600 border border-green-400 flex items-center">
                                    <i class="fa-solid fa-file-excel mr-2"></i> Export Excel
                                </button>
                            </div>
                        </div>

                        <div class="bg-white/40 p-4 rounded-xl border border-white/60 mb-6 flex flex-col sm:flex-row gap-6 no-print">
                            <div>
                                <label class="block text-xs font-black text-gray-900 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                                <input type="date" x-model="reportStartDate" class="pl-3 pr-6 py-2 border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-900 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                                <input type="date" x-model="reportEndDate" class="pl-3 pr-6 py-2 border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 print-only-cards">
                            <div class="bg-gradient-to-br from-green-400 to-emerald-500 border border-green-300 p-4 rounded-xl shadow-md text-white">
                                <p class="text-xs font-black uppercase mb-1"><i class="fa-solid fa-award mr-1"></i> Rajin</p>
                                <p class="text-xl font-black truncate" x-text="reportHighlights.palingRajin"></p>
                            </div>
                            <div class="bg-gradient-to-br from-yellow-400 to-orange-400 border border-yellow-300 p-4 rounded-xl shadow-md text-white">
                                <p class="text-xs font-black uppercase mb-1"><i class="fa-solid fa-chalkboard-user mr-1"></i> Tugas</p>
                                <p class="text-xl font-black truncate" x-text="reportHighlights.seringTugas"></p>
                            </div>
                            <div class="bg-gradient-to-br from-red-400 to-rose-500 border border-red-300 p-4 rounded-xl shadow-md text-white">
                                <p class="text-xs font-black uppercase mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Alpa</p>
                                <p class="text-xl font-black truncate" x-text="reportHighlights.seringAbsen"></p>
                            </div>
                        </div>

                        <div class="overflow-x-auto bg-white/30 backdrop-blur-md rounded-xl border border-white/50 shadow-sm">
                            <table class="min-w-full text-sm border-collapse">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Nama Guru</th>
                                        <th class="px-4 py-3 text-center font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Total Jadwal</th>
                                        <th class="px-4 py-3 text-center font-black text-green-700 uppercase bg-white/40 border-b border-white/60">Hadir</th>
                                        <th class="px-4 py-3 text-center font-black text-yellow-700 uppercase bg-white/40 border-b border-white/60">Tugas</th>
                                        <th class="px-4 py-3 text-center font-black text-red-700 uppercase bg-white/40 border-b border-white/60">Alpa/Tdk Hadir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/40 bg-white/20">
                                    <template x-for="row in laporanKehadiran" :key="row.nama">
                                        <tr class="hover:bg-white/40 transition">
                                            <td class="px-4 py-3 whitespace-nowrap font-black text-gray-900 drop-shadow-sm" x-text="row.nama"></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center font-bold text-gray-800" x-text="row.totalJadwal"></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center font-black text-green-600" x-text="row.hadir"></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center font-black text-yellow-600" x-text="row.tugas"></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center font-black text-red-600" x-text="row.tidakHadir"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="laporanKehadiran.length === 0">
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-800 font-bold">Tidak ada data untuk periode ini.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB: KM DASHBOARD (ABSENSI KELAS) -->
                <div x-show="currentTab === 'km_dashboard'" x-cloak>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="glass-panel p-5 rounded-2xl border-b-4 border-b-blue-500">
                            <p class="text-xs sm:text-sm text-gray-700 font-bold mb-1 drop-shadow-sm">TOTAL SISWA KELAS</p>
                            <p class="text-3xl font-black text-gray-900" x-text="kmStatistik.total"></p>
                        </div>
                        <div class="glass-panel p-5 rounded-2xl border-b-4 border-b-green-500">
                            <p class="text-xs sm:text-sm text-gray-700 font-bold mb-1 drop-shadow-sm">SUDAH DIABSEN</p>
                            <p class="text-3xl font-black text-gray-900" x-text="kmStatistik.sudah"></p>
                        </div>
                        <div class="glass-panel p-5 rounded-2xl border-b-4 border-b-yellow-500">
                            <p class="text-xs sm:text-sm text-gray-700 font-bold mb-1 drop-shadow-sm">HADIR</p>
                            <p class="text-3xl font-black text-gray-900" x-text="kmStatistik.hadir"></p>
                        </div>
                        <div class="glass-panel p-5 rounded-2xl border-b-4 border-b-red-500">
                            <p class="text-xs sm:text-sm text-gray-700 font-bold mb-1 drop-shadow-sm">BELUM DIABSEN</p>
                            <p class="text-3xl font-black text-gray-900" x-text="kmStatistik.belum"></p>
                        </div>
                    </div>

                    <div class="glass-panel p-6 rounded-3xl mb-6 border-2 border-yellow-300 bg-gradient-to-r from-yellow-50/50 to-white/50">
                        <h3 class="font-black text-gray-900 text-lg mb-4 border-b border-yellow-300/50 pb-3"><i class="fa-solid fa-bullhorn text-yellow-600 mr-2"></i>Tugas dari Guru Hari Ini</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-for="tugas in tugasHariIni" :key="tugas.id">
                                <div class="bg-white/80 p-4 rounded-xl border border-yellow-400 shadow-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="font-black text-gray-900" x-text="tugas.mapel"></p>
                                        <span class="bg-yellow-100 text-yellow-800 text-[10px] font-black px-2 py-1 rounded-md border border-yellow-300" x-text="`Jam ${tugas.jamke}`"></span>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700 mb-3" x-text="tugas.guru"></p>
                                    <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                        <p class="text-xs font-black text-yellow-800 uppercase mb-1">Instruksi Tugas:</p>
                                        <p class="text-sm font-bold text-gray-800" x-text="tugas.deskripsiTugas || 'Tidak ada deskripsi'"></p>
                                    </div>
                                </div>
                            </template>
                            <div x-show="tugasHariIni.length === 0" class="col-span-full text-center py-6 text-gray-500 font-bold">
                                Belum ada tugas yang diberikan oleh guru pada hari ini.
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1 space-y-6">
                            <div class="glass-panel p-6 rounded-3xl">
                                <h3 class="font-black text-gray-900 text-lg mb-4 border-b border-white/40 pb-3"><i class="fa-solid fa-calendar-day text-purple-600 mr-2"></i>Pilih Tanggal Absensi</h3>
                                <input type="date" x-model="selectedTanggalKM" @change="loadKmAttendanceData()" class="w-full px-4 py-3 border border-white/60 rounded-xl bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-lg">
                                <div class="mt-4 p-4 bg-blue-100/50 border border-blue-200 rounded-xl text-sm font-bold text-blue-900">
                                    <i class="fa-solid fa-info-circle mr-1"></i> Data disimpan langsung ke Cloud Firestore.
                                </div>
                            </div>
                            
                            <div class="glass-panel p-6 rounded-3xl">
                                <h3 class="font-black text-gray-900 text-lg mb-4 border-b border-white/40 pb-3"><i class="fa-solid fa-book-open text-purple-600 mr-2"></i>Jadwal Hari Ini</h3>
                                <div class="space-y-3 max-h-64 overflow-y-auto pr-2 scrollbar-thin">
                                    <template x-for="j in kmJadwalHariIni">
                                        <div class="bg-white/50 p-3 rounded-xl border border-white/60 shadow-sm">
                                            <p class="font-black text-gray-900" x-text="j.mapel"></p>
                                            <p class="text-sm font-bold text-gray-700" x-text="j.guru"></p>
                                            <p class="text-xs font-bold text-purple-800 mt-1" x-text="`Jam Ke-${j.jamke} (${j.waktu})`"></p>
                                        </div>
                                    </template>
                                    <div x-show="kmJadwalHariIni.length === 0" class="text-center py-4 text-gray-500 font-bold">
                                        Tidak ada jadwal pelajaran hari ini.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="glass-panel p-6 rounded-3xl h-full flex flex-col">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 border-b border-white/40 pb-4 gap-3">
                                    <h3 class="font-black text-gray-900 text-xl drop-shadow-sm">
                                        <i class="fa-solid fa-list-check text-purple-600 mr-2"></i>Daftar Absensi Siswa
                                    </h3>
                                    <div class="flex gap-2">
                                        <button @click="markAllPresentKM()" :disabled="!isKmEditAllowed(false)" :class="!isKmEditAllowed(false) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-emerald-600'" class="px-4 py-2 bg-emerald-500/90 text-white text-sm font-bold rounded-xl shadow transition border border-emerald-400">
                                            <i class="fa-solid fa-check mr-1"></i> Tandai Semua Hadir
                                        </button>
                                        <button @click="saveKmAttendance()" :disabled="!isKmEditAllowed(false)" :class="!isKmEditAllowed(false) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'" class="px-4 py-2 bg-blue-600/90 text-white text-sm font-bold rounded-xl shadow transition border border-blue-500">
                                            <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Absensi
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="overflow-y-auto flex-1 pr-2 scrollbar-thin space-y-3" style="max-height: 600px;">
                                    <template x-for="(siswa, index) in draftAbsensiKM" :key="siswa.siswaId">
                                        <div class="glass-card p-4 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-all hover:shadow-md">
                                            <div class="w-full sm:w-1/3">
                                                <p class="font-black text-gray-900 text-lg line-clamp-1" x-text="siswa.nama"></p>
                                                <p class="text-sm font-bold text-gray-600" x-text="`NIS: ${siswa.nis}`"></p>
                                            </div>
                                            
                                            <div class="w-full sm:w-auto flex flex-wrap gap-2">
                                                <label class="status-radio hadir cursor-pointer">
                                                    <input type="radio" :name="`status_${siswa.siswaId}`" value="Hadir" x-model="siswa.status" class="hidden" :disabled="!isKmEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Hadir</div>
                                                </label>
                                                <label class="status-radio terlambat cursor-pointer">
                                                    <input type="radio" :name="`status_${siswa.siswaId}`" value="Terlambat" x-model="siswa.status" class="hidden" :disabled="!isKmEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Telat</div>
                                                </label>
                                                <label class="status-radio izin cursor-pointer">
                                                    <input type="radio" :name="`status_${siswa.siswaId}`" value="Izin" x-model="siswa.status" class="hidden" :disabled="!isKmEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Izin</div>
                                                </label>
                                                <label class="status-radio sakit cursor-pointer">
                                                    <input type="radio" :name="`status_${siswa.siswaId}`" value="Sakit" x-model="siswa.status" class="hidden" :disabled="!isKmEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Sakit</div>
                                                </label>
                                                <label class="status-radio alpa cursor-pointer">
                                                    <input type="radio" :name="`status_${siswa.siswaId}`" value="Alpa" x-model="siswa.status" class="hidden" :disabled="!isKmEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Alpa</div>
                                                </label>
                                            </div>
                                            
                                            <div class="w-full sm:w-1/4">
                                                <input type="text" x-model="siswa.catatan" placeholder="Catatan (Opsional)" :disabled="!isKmEditAllowed(false)" class="w-full px-3 py-1.5 text-sm border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 disabled:opacity-50">
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <div x-show="draftAbsensiKM.length === 0" class="text-center py-10 text-gray-500 font-bold border-2 border-dashed border-gray-400/50 rounded-2xl bg-white/30 backdrop-blur">
                                        <i class="fa-solid fa-users-slash text-4xl mb-3 text-gray-400"></i>
                                        <p class="text-lg">Tidak ada data siswa untuk kelas <span x-text="currentUser.kelas_km"></span>.</p>
                                        <p class="text-sm mt-1">Pastikan Administrator telah mendaftarkan siswa pada menu Master Siswa.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: GURU ABSENSI SISWA -->
                <div x-show="currentTab === 'guru_absensi_siswa'" x-cloak>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1 space-y-6">
                            <div class="glass-panel p-6 rounded-3xl">
                                <h3 class="font-black text-gray-900 text-lg mb-4 border-b border-white/40 pb-3"><i class="fa-solid fa-calendar-day text-purple-600 mr-2"></i>Pilih Tanggal Absensi</h3>
                                <input type="date" x-model="selectedTanggalGuru" class="w-full px-4 py-3 border border-white/60 rounded-xl bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-lg mb-4">
                                
                                <h3 class="font-black text-gray-900 text-lg mb-4 border-b border-white/40 pb-3 mt-4"><i class="fa-solid fa-door-open text-purple-600 mr-2"></i>Pilih Kelas</h3>
                                <select x-model="selectedKelasGuru" class="w-full px-4 py-3 border border-white/60 rounded-xl bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-lg">
                                    <option value="">-- Pilih Kelas --</option>
                                    <template x-for="k in kelasGuruSaya"><option :value="k" x-text="k"></option></template>
                                </select>
                                
                                <div class="mt-6 p-4 bg-purple-100/50 border border-purple-200 rounded-xl text-sm font-bold text-purple-900">
                                    <i class="fa-solid fa-info-circle mr-1"></i> Absensi langsung disinkronkan ke Database Sekolah.
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="glass-panel p-6 rounded-3xl h-full flex flex-col">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 border-b border-white/40 pb-4 gap-3">
                                    <h3 class="font-black text-gray-900 text-xl drop-shadow-sm">
                                        <i class="fa-solid fa-list-check text-purple-600 mr-2"></i>Daftar Siswa <span x-show="selectedKelasGuru">Kelas <span x-text="selectedKelasGuru"></span></span>
                                    </h3>
                                    <div class="flex gap-2">
                                        <button @click="markAllPresentGuru()" :disabled="!isGuruEditAllowed(false) || !selectedKelasGuru" :class="(!isGuruEditAllowed(false) || !selectedKelasGuru) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-emerald-600'" class="px-4 py-2 bg-emerald-500/90 text-white text-sm font-bold rounded-xl shadow transition border border-emerald-400">
                                            <i class="fa-solid fa-check mr-1"></i> Tandai Semua Hadir
                                        </button>
                                        <button @click="saveGuruAttendance()" :disabled="!isGuruEditAllowed(false) || !selectedKelasGuru" :class="(!isGuruEditAllowed(false) || !selectedKelasGuru) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'" class="px-4 py-2 bg-blue-600/90 text-white text-sm font-bold rounded-xl shadow transition border border-blue-500">
                                            <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Absensi
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="overflow-y-auto flex-1 pr-2 scrollbar-thin space-y-3" style="max-height: 600px;">
                                    <template x-for="(siswa, index) in draftAbsensiGuru" :key="siswa.siswaId">
                                        <div class="glass-card p-4 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-all hover:shadow-md">
                                            <div class="w-full sm:w-1/3">
                                                <p class="font-black text-gray-900 text-lg line-clamp-1" x-text="siswa.nama"></p>
                                                <p class="text-sm font-bold text-gray-600" x-text="`NIS: ${siswa.nis}`"></p>
                                            </div>
                                            
                                            <div class="w-full sm:w-auto flex flex-wrap gap-2">
                                                <label class="status-radio hadir cursor-pointer">
                                                    <input type="radio" :name="`guru_status_${siswa.siswaId}`" value="Hadir" x-model="siswa.status" class="hidden" :disabled="!isGuruEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Hadir</div>
                                                </label>
                                                <label class="status-radio terlambat cursor-pointer">
                                                    <input type="radio" :name="`guru_status_${siswa.siswaId}`" value="Terlambat" x-model="siswa.status" class="hidden" :disabled="!isGuruEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Telat</div>
                                                </label>
                                                <label class="status-radio izin cursor-pointer">
                                                    <input type="radio" :name="`guru_status_${siswa.siswaId}`" value="Izin" x-model="siswa.status" class="hidden" :disabled="!isGuruEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Izin</div>
                                                </label>
                                                <label class="status-radio sakit cursor-pointer">
                                                    <input type="radio" :name="`guru_status_${siswa.siswaId}`" value="Sakit" x-model="siswa.status" class="hidden" :disabled="!isGuruEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Sakit</div>
                                                </label>
                                                <label class="status-radio alpa cursor-pointer">
                                                    <input type="radio" :name="`guru_status_${siswa.siswaId}`" value="Alpa" x-model="siswa.status" class="hidden" :disabled="!isGuruEditAllowed(false)">
                                                    <div class="px-3 py-1.5 rounded-lg text-xs font-black border border-gray-300 bg-white text-gray-700 transition">Alpa</div>
                                                </label>
                                            </div>
                                            
                                            <div class="w-full sm:w-1/4">
                                                <input type="text" x-model="siswa.catatan" placeholder="Catatan..." :disabled="!isGuruEditAllowed(false)" class="w-full px-3 py-1.5 text-sm border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 disabled:opacity-50">
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <div x-show="draftAbsensiGuru.length === 0" class="text-center py-10 text-gray-500 font-bold border-2 border-dashed border-gray-400/50 rounded-2xl bg-white/30 backdrop-blur">
                                        <i class="fa-solid fa-chalkboard-user text-4xl mb-3 text-gray-400"></i>
                                        <p class="text-lg" x-text="selectedKelasGuru ? 'Tidak ada data siswa untuk kelas ini.' : 'Silakan pilih Kelas terlebih dahulu pada panel kiri.'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: GURU LAPORAN SISWA -->
                <div x-show="currentTab === 'guru_laporan_siswa'" x-cloak>
                    <div class="glass-panel p-6 rounded-3xl mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 no-print">
                            <h3 class="font-black text-gray-900 text-xl flex items-center drop-shadow-sm">
                                <i class="fa-solid fa-file-lines text-purple-600 mr-3"></i> History Absensi Kelas (Role Guru)
                            </h3>
                            <div class="flex gap-2">
                                <button @click="printPDF()" class="px-4 py-2 bg-red-400/90 text-white text-sm font-bold rounded-lg shadow transition hover:bg-red-500 border border-red-300 flex items-center">
                                    <i class="fa-solid fa-file-pdf mr-2"></i> Print PDF
                                </button>
                                <button @click="downloadLaporanSiswaGuruExcel()" class="px-4 py-2 bg-green-500/90 text-white text-sm font-bold rounded-lg shadow transition hover:bg-green-600 border border-green-400 flex items-center">
                                    <i class="fa-solid fa-file-excel mr-2"></i> Export Excel
                                </button>
                            </div>
                        </div>

                        <div class="bg-white/40 p-4 rounded-xl border border-white/60 mb-6 flex flex-wrap gap-4 no-print">
                            <div>
                                <label class="block text-xs font-black text-gray-900 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                                <input type="date" x-model="reportSiswaGuruStartDate" class="pl-3 pr-6 py-2 border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-900 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                                <input type="date" x-model="reportSiswaGuruEndDate" class="pl-3 pr-6 py-2 border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-900 mb-1 uppercase tracking-wider">Filter Kelas</label>
                                <select x-model="reportSiswaGuruKelas" class="px-3 py-2 border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm min-w-[150px]">
                                    <option value="Semua">Semua Kelas Saya</option>
                                    <template x-for="k in kelasGuruSaya"><option :value="k" x-text="k"></option></template>
                                </select>
                            </div>
                        </div>

                        <div class="overflow-x-auto bg-white/30 backdrop-blur-md rounded-xl border border-white/50 shadow-sm">
                            <table class="min-w-full text-sm border-collapse">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Tanggal</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Kelas</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">NIS</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Nama Lengkap</th>
                                        <th class="px-4 py-3 text-center font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Status</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/40 bg-white/20">
                                    <template x-for="row in laporanSiswaGuruList" :key="row.id">
                                        <tr class="hover:bg-white/40 transition">
                                            <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-800" x-text="row.tanggal"></td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 py-1 bg-white/70 border border-white/80 text-purple-900 rounded font-black text-xs shadow-sm" x-text="row.kelas"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-800" x-text="row.nis"></td>
                                            <td class="px-4 py-3 whitespace-nowrap font-black text-gray-900 drop-shadow-sm" x-text="row.nama"></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <span class="px-3 py-1 text-xs font-black rounded-lg shadow-sm border border-white/50 inline-block w-24 text-center"
                                                    :class="{
                                                        'bg-green-500 text-white': row.status === 'Hadir',
                                                        'bg-yellow-500 text-white': row.status === 'Terlambat',
                                                        'bg-orange-500 text-white': row.status === 'Sakit',
                                                        'bg-blue-500 text-white': row.status === 'Izin',
                                                        'bg-red-500 text-white': row.status === 'Alpa'
                                                    }" x-text="row.status">
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-xs font-bold text-gray-700" x-text="row.catatan"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="laporanSiswaGuruList.length === 0">
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-800 font-bold">Tidak ada history absensi untuk filter yang dipilih.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB: LAPORAN SISWA (ADMIN) -->
                <div x-show="currentTab === 'laporan_siswa'" x-cloak>
                    <div class="glass-panel p-6 rounded-3xl mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 no-print">
                            <h3 class="font-black text-gray-900 text-xl flex items-center drop-shadow-sm">
                                <i class="fa-solid fa-chart-bar text-purple-600 mr-3"></i> History & Statistik Absensi Siswa
                            </h3>
                            <div class="flex gap-2">
                                <button @click="printPDF()" class="px-4 py-2 bg-red-400/90 text-white text-sm font-bold rounded-lg shadow transition hover:bg-red-500 border border-red-300 flex items-center">
                                    <i class="fa-solid fa-file-pdf mr-2"></i> Print PDF
                                </button>
                                <button @click="downloadLaporanSiswaExcel()" class="px-4 py-2 bg-green-500/90 text-white text-sm font-bold rounded-lg shadow transition hover:bg-green-600 border border-green-400 flex items-center">
                                    <i class="fa-solid fa-file-excel mr-2"></i> Export Excel
                                </button>
                            </div>
                        </div>

                        <!-- Date Range Pickers for Admin + Tombol Filter Data -->
                        <div class="bg-white/40 p-4 rounded-xl border border-white/60 mb-6 flex flex-wrap items-end gap-4 no-print">
                            <div>
                                <label class="block text-xs font-black text-gray-900 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                                <input type="date" x-model="reportAdminSiswaStartDate" class="pl-3 pr-6 py-2 border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-900 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                                <input type="date" x-model="reportAdminSiswaEndDate" class="pl-3 pr-6 py-2 border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-900 mb-1 uppercase tracking-wider">Filter Kelas</label>
                                <select x-model="filterKelasLaporanSiswa" class="px-3 py-2 border border-white/60 rounded-lg bg-white/60 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm min-w-[150px]">
                                    <option value="Semua">Semua Kelas</option>
                                    <template x-for="k in masterKelas"><option :value="k.col1" x-text="k.col1"></option></template>
                                </select>
                            </div>
                            <div>
                                <button @click="fetchAdminLaporanSiswa()" :disabled="isLoading" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm rounded-lg shadow transition flex items-center gap-2">
                                    <i class="fa-solid fa-filter" :class="{'fa-spin': isLoading}"></i>
                                    <span>Tampilkan Data</span>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6 print-only-cards">
                            <div class="bg-white/60 border border-gray-200 p-3 rounded-xl shadow-sm text-center">
                                <p class="text-xs font-black text-gray-600 uppercase">Hadir</p>
                                <p class="text-xl font-black text-green-600" x-text="statistikSiswaAdmin.Hadir"></p>
                            </div>
                            <div class="bg-white/60 border border-gray-200 p-3 rounded-xl shadow-sm text-center">
                                <p class="text-xs font-black text-gray-600 uppercase">Terlambat</p>
                                <p class="text-xl font-black text-yellow-600" x-text="statistikSiswaAdmin.Terlambat"></p>
                            </div>
                            <div class="bg-white/60 border border-gray-200 p-3 rounded-xl shadow-sm text-center">
                                <p class="text-xs font-black text-gray-600 uppercase">Sakit</p>
                                <p class="text-xl font-black text-orange-500" x-text="statistikSiswaAdmin.Sakit"></p>
                            </div>
                            <div class="bg-white/60 border border-gray-200 p-3 rounded-xl shadow-sm text-center">
                                <p class="text-xs font-black text-gray-600 uppercase">Izin</p>
                                <p class="text-xl font-black text-blue-500" x-text="statistikSiswaAdmin.Izin"></p>
                            </div>
                            <div class="bg-white/60 border border-gray-200 p-3 rounded-xl shadow-sm text-center">
                                <p class="text-xs font-black text-gray-600 uppercase">Alpa</p>
                                <p class="text-xl font-black text-red-600" x-text="statistikSiswaAdmin.Alpa"></p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 border border-purple-400 p-3 rounded-xl shadow-sm text-center flex flex-col justify-center text-white">
                                <p class="text-xs font-black uppercase text-purple-100">% Hadir</p>
                                <p class="text-2xl font-black" x-text="statistikSiswaAdmin.Persentase + '%'"></p>
                            </div>
                        </div>

                        <div class="bg-white/50 p-4 rounded-xl border border-white/60 mb-6 w-full h-64 no-print flex items-center justify-center">
                            <canvas id="attendanceChart"></canvas>
                        </div>

                        <div class="overflow-x-auto bg-white/30 backdrop-blur-md rounded-xl border border-white/50 shadow-sm">
                            <table class="min-w-full text-sm border-collapse">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Tanggal</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Kelas</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">NIS</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Nama Lengkap</th>
                                        <th class="px-4 py-3 text-center font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Status</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase bg-white/40 border-b border-white/60">Catatan</th>
                                        <th class="px-4 py-3 text-center font-black text-gray-900 uppercase bg-white/40 border-b border-white/60 no-print">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/40 bg-white/20">
                                    <template x-for="row in laporanAdminSiswaHistory" :key="row.id">
                                        <tr class="hover:bg-white/40 transition">
                                            <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-800" x-text="row.tanggal"></td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 py-1 bg-white/70 border border-white/80 text-purple-900 rounded font-black text-xs shadow-sm" x-text="row.kelas"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-800" x-text="row.nis"></td>
                                            <td class="px-4 py-3 whitespace-nowrap font-black text-gray-900 drop-shadow-sm" x-text="row.nama"></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <span class="px-3 py-1 text-xs font-black rounded-lg shadow-sm border border-white/50 inline-block w-24 text-center"
                                                    :class="{
                                                        'bg-green-500 text-white': row.status === 'Hadir',
                                                        'bg-yellow-500 text-white': row.status === 'Terlambat',
                                                        'bg-orange-500 text-white': row.status === 'Sakit',
                                                        'bg-blue-500 text-white': row.status === 'Izin',
                                                        'bg-red-500 text-white': row.status === 'Alpa'
                                                    }" x-text="row.status">
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-xs font-bold text-gray-700" x-text="row.catatan"></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center no-print">
                                                <button @click="editKoreksiSiswa(row)" class="px-3 py-1.5 bg-blue-100 text-blue-700 border border-blue-300 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-black transition shadow-sm">
                                                    Koreksi
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="laporanAdminSiswaHistory.length === 0">
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-800 font-bold">Tidak ada data history untuk rentang waktu ini. Silakan tentukan tanggal dan klik tombol "Tampilkan Data".</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB: JADWAL SAYA -->
                <div x-show="currentTab === 'jadwal_saya'" x-cloak>
                    <div class="glass-panel p-4 sm:p-6 rounded-3xl">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 border-b border-white/40 pb-4 no-print">
                            <h3 class="font-black text-gray-900 text-lg sm:text-xl drop-shadow-sm">
                                <i class="fa-solid fa-calendar-check text-purple-600 mr-2"></i>Semua Jadwal Mengajar Saya
                            </h3>
                            <div class="flex gap-2 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0 scrollbar-thin">
                                <template x-for="hari in ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']">
                                    <button @click="selectedDayGuru = hari"
                                        class="px-4 py-2 text-sm font-black rounded-xl shadow-sm transition border whitespace-nowrap"
                                        :class="selectedDayGuru === hari ? 'bg-purple-600 text-white border-purple-500' : 'bg-white/50 text-gray-700 border-white/60 hover:bg-white/80'"
                                        x-text="hari"></button>
                                </template>
                            </div>
                        </div>

                        <div class="overflow-x-auto bg-white/20 backdrop-blur-md rounded-xl border border-white/40 shadow-inner">
                            <table class="min-w-full text-sm border-collapse">
                                <thead class="bg-white/40 border-b border-white/50">
                                    <tr>
                                        <th class="px-4 py-3 text-center font-black text-gray-900 uppercase drop-shadow-sm w-24">Jam Ke</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm w-32">Waktu</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm w-32">Kelas</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Mata Pelajaran</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/30">
                                    <template x-for="jadwal in filteredGuruSchedules" :key="jadwal.id">
                                        <tr class="hover:bg-white/50 transition">
                                            <td class="px-4 py-4 whitespace-nowrap text-center font-black text-gray-900 drop-shadow-sm" x-text="jadwal.jamKe"></td>
                                            <td class="px-4 py-4 whitespace-nowrap font-bold text-gray-800" x-text="jadwal.waktu"></td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 bg-white/70 border border-white/80 text-purple-900 rounded-md font-black text-xs shadow-sm" x-text="jadwal.kelas"></span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap font-black text-gray-900 drop-shadow-sm" x-text="jadwal.mapel"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="filteredGuruSchedules.length === 0">
                                        <td colspan="4" class="px-4 py-12 text-center">
                                            <i class="fa-solid fa-mug-hot text-4xl mb-3 text-gray-400 drop-shadow-md"></i>
                                            <p class="text-gray-700 font-bold">Tidak ada jadwal mengajar pada hari <span x-text="selectedDayGuru"></span>.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB: KOREKSI & LIBUR -->
                <div x-show="currentTab === 'koreksi_libur'" x-cloak>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Panel Koreksi Kehadiran -->
                        <div class="glass-panel p-6 rounded-3xl h-full flex flex-col">
                            <h3 class="font-black text-gray-900 text-xl flex items-center drop-shadow-sm mb-6 border-b border-white/40 pb-3">
                                <i class="fa-solid fa-user-pen text-purple-600 mr-3"></i> Koreksi Kehadiran Guru
                            </h3>
                            
                            <div class="flex flex-col sm:flex-row gap-4 mb-6 bg-white/40 p-4 rounded-xl border border-white/60 shadow-sm">
                                <div class="flex-1">
                                    <label class="block text-[10px] font-black text-gray-600 mb-1 uppercase tracking-wider">Pilih Tanggal</label>
                                    <input type="date" x-model="koreksiGuruForm.tanggal" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/70 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm transition shadow-sm">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[10px] font-black text-gray-600 mb-1 uppercase tracking-wider">Pilih Guru</label>
                                    <select x-model="koreksiGuruForm.guru" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/70 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm transition shadow-sm">
                                        <option value="">-- Pilih Nama Guru --</option>
                                        <template x-for="g in masterGuru.sort((a,b)=>(a.col1||'').localeCompare(b.col1||''))" :key="g.id">
                                            <option :value="g.col1" x-text="g.col1"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex-1 space-y-3 max-h-[500px] overflow-y-auto pr-2 scrollbar-thin">
                                <template x-if="!koreksiGuruForm.guru">
                                    <div class="text-center py-10 border-2 border-dashed border-gray-400/50 rounded-2xl bg-white/30 backdrop-blur">
                                        <i class="fa-solid fa-magnifying-glass text-3xl mb-3 text-gray-400"></i>
                                        <p class="text-gray-600 font-bold">Pilih Nama Guru untuk melihat jadwal.</p>
                                    </div>
                                </template>
                                
                                <template x-if="koreksiGuruForm.guru && koreksiGuruList.length === 0">
                                    <div class="text-center py-10 border-2 border-dashed border-gray-400/50 rounded-2xl bg-white/30 backdrop-blur">
                                        <p class="text-gray-600 font-bold">Tidak ada jadwal untuk guru ini pada tanggal tersebut.</p>
                                    </div>
                                </template>
                                
                                <template x-for="item in koreksiGuruList" :key="item.jadwal.id">
                                    <div class="bg-white/60 p-4 rounded-xl border border-white/60 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition hover:bg-white/80">
                                        <div>
                                            <p class="font-black text-gray-900" x-text="item.jadwal.mapel"></p>
                                            <p class="text-xs font-bold text-purple-800" x-text="`Kelas ${item.jadwal.kelas} | Jam Ke-${item.jadwal.jamKe} (${item.jadwal.waktu})`"></p>
                                            <span class="mt-2 inline-block px-2 py-1 text-[10px] font-black rounded-md shadow-sm border border-white/50"
                                                :class="{
                                                    'bg-green-500 text-white': item.status === 'HADIR',
                                                    'bg-yellow-500 text-white': item.status === 'TUGAS',
                                                    'bg-red-500 text-white': item.status === 'ALPA',
                                                    'bg-gray-200 text-gray-600': item.status === 'BELUM ADA INFO'
                                                }" x-text="item.status"></span>
                                        </div>
                                        <div x-show="item.status === 'ALPA' || item.status === 'BELUM ADA INFO'">
                                            <button @click="ubahKeHadirAdmin(item)" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black rounded-lg shadow-md transition transform hover:scale-105 border border-emerald-400">
                                                <i class="fa-solid fa-check mr-1"></i> Ubah HADIR
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Panel Peniadaan Absensi -->
                        <div class="glass-panel p-6 rounded-3xl h-full flex flex-col">
                            <h3 class="font-black text-gray-900 text-xl flex items-center drop-shadow-sm mb-6 border-b border-white/40 pb-3">
                                <i class="fa-solid fa-umbrella-beach text-purple-600 mr-3"></i> Peniadaan Absensi (Libur)
                            </h3>
                            
                            <div class="bg-blue-50/70 p-4 rounded-xl border border-blue-200 mb-6 text-sm font-bold text-blue-900 shadow-sm">
                                <i class="fa-solid fa-circle-info mr-1"></i> Atur hari libur nasional atau acara khusus. Sistem <b class="text-red-600">Auto-Alpa</b> otomatis nonaktif pada tanggal ini, dan status absen Guru tidak akan dihitung di Laporan.
                            </div>
                            
                            <div class="bg-white/40 p-4 rounded-xl border border-white/60 mb-6 flex flex-col gap-4 shadow-sm">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 mb-1 uppercase tracking-wider">Tanggal Libur</label>
                                    <input type="date" x-model="liburForm.tanggal" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/70 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm transition shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 mb-1 uppercase tracking-wider">Keterangan / Alasan</label>
                                    <input type="text" x-model="liburForm.keterangan" placeholder="Contoh: Libur Nasional Idul Fitri" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/70 backdrop-blur outline-none focus:ring-2 focus:ring-purple-400 font-bold text-gray-900 text-sm transition shadow-sm">
                                </div>
                                <div class="text-right mt-1">
                                    <button @click="simpanLibur()" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-black rounded-xl shadow-md transition transform hover:scale-105 border border-blue-500">
                                        <i class="fa-solid fa-plus mr-1"></i> Simpan Libur
                                    </button>
                                </div>
                            </div>
                            
                            <h4 class="font-black text-gray-800 text-sm mb-3 uppercase tracking-wider">Daftar Hari Libur Terdaftar</h4>
                            <div class="flex-1 overflow-y-auto pr-2 scrollbar-thin space-y-3 min-h-[150px]">
                                <template x-for="h in holidays.sort((a,b) => (a.tanggal||'').localeCompare(b.tanggal||'')).reverse()" :key="h.id">
                                    <div class="bg-white/60 p-3 rounded-xl border border-white/60 shadow-sm flex justify-between items-center transition hover:bg-white/80">
                                        <div>
                                            <p class="font-black text-gray-900" x-text="h.tanggal"></p>
                                            <p class="text-xs font-bold text-gray-700 mt-0.5" x-text="h.keterangan"></p>
                                        </div>
                                        <button @click="hapusLibur(h.id)" class="w-8 h-8 rounded-full bg-red-100/80 text-red-600 hover:bg-red-500 hover:text-white transition flex items-center justify-center border border-red-200">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </template>
                                <template x-if="holidays.length === 0">
                                    <div class="text-center py-6 text-gray-500 font-bold text-sm border-2 border-dashed border-gray-400/50 rounded-xl">
                                        Belum ada hari libur yang diatur.
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: MASTER DATA -->
                <div x-show="['master_guru', 'master_kelas', 'master_mapel', 'master_siswa', 'user', 'jadwal'].includes(currentTab)" x-cloak>
                    <div class="glass-panel p-4 sm:p-6 rounded-3xl">
                        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-6 gap-4 border-b border-white/40 pb-4">
                            <h3 class="font-black text-gray-900 text-lg sm:text-xl drop-shadow-sm" x-text="getModalTitle()"></h3>
                            <div class="flex flex-wrap gap-2 w-full xl:w-auto justify-start xl:justify-end">
                                <button x-show="currentTab === 'user'" @click="generateKmAccounts()" class="flex-1 sm:flex-none flex justify-center items-center px-4 py-2.5 bg-purple-500/80 backdrop-blur-md hover:bg-purple-600/90 text-white text-sm font-bold rounded-xl shadow-md whitespace-nowrap transition border border-purple-400">
                                    <i class="fa-solid fa-wand-magic-sparkles mr-1"></i> Generate Akun KM
                                </button>
                                <button x-show="currentTab === 'master_siswa'" @click="showTransferModal = true" class="flex-1 sm:flex-none flex justify-center items-center px-4 py-2.5 bg-orange-500/80 backdrop-blur-md hover:bg-orange-600/90 text-white text-sm font-bold rounded-xl shadow-md whitespace-nowrap transition border border-orange-400">
                                    <i class="fa-solid fa-right-left mr-1"></i> Pindah Kelas Masal
                                </button>
                                <button x-show="getActiveDataList().length > (currentTab === 'user' ? 1 : 0)" @click="promptDeleteAll()" class="flex-1 sm:flex-none flex justify-center items-center px-4 py-2.5 bg-red-500/80 backdrop-blur-md hover:bg-red-600/90 text-white text-sm font-bold rounded-xl shadow-[0_4px_15px_rgba(239,68,68,0.3)] whitespace-nowrap transition border border-red-400/50">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Hapus Semua
                                </button>
                                <button x-show="currentTab !== 'jadwal'" @click="downloadTemplate()" class="flex-1 sm:flex-none flex justify-center items-center px-4 py-2.5 bg-teal-500/80 backdrop-blur-md border border-teal-400 hover:bg-teal-600/90 text-white text-sm font-bold rounded-xl shadow-md whitespace-nowrap transition">
                                    <i class="fa-solid fa-download mr-1"></i> Template Excel
                                </button>
                                <button x-show="currentTab === 'jadwal'" @click="downloadScheduleTemplateExcel()" class="flex-1 sm:flex-none flex justify-center items-center px-4 py-2.5 bg-teal-500/80 backdrop-blur-md border border-teal-400 hover:bg-teal-600/90 text-white text-sm font-bold rounded-xl shadow-md whitespace-nowrap transition">
                                    <i class="fa-solid fa-download mr-1"></i> Template
                                </button>
                                <label class="flex-1 sm:flex-none flex justify-center items-center px-4 py-2.5 bg-emerald-500/80 backdrop-blur-md border border-emerald-400 hover:bg-emerald-600/90 text-white text-sm font-bold rounded-xl shadow-md whitespace-nowrap transition cursor-pointer">
                                    <i class="fa-solid fa-file-import mr-1"></i> Import
                                    <input type="file" accept=".xlsx, .xls" class="hidden" @change="currentTab === 'jadwal' ? handleScheduleUpload($event) : handleFileImport($event)">
                                </label>
                                <button @click="exportDataExcel()" class="flex-1 sm:flex-none flex justify-center items-center px-4 py-2.5 bg-blue-500/80 backdrop-blur-md border border-blue-400 hover:bg-blue-600/90 text-white text-sm font-bold rounded-xl shadow-md whitespace-nowrap transition">
                                    <i class="fa-solid fa-file-export mr-1"></i> Export Excel
                                </button>
                                <button @click="openModal('tambah')" class="w-full sm:w-auto flex justify-center items-center px-4 py-2.5 bg-gradient-to-r from-blue-600/80 to-purple-600/80 backdrop-blur-md border border-purple-400 hover:from-blue-700 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-md whitespace-nowrap transition">
                                    <i class="fa-solid fa-plus mr-1"></i> Tambah Baru
                                </button>
                            </div>
                        </div>

                        <!-- SUB-TAB: MASTER GURU/KELAS/MAPEL/USER -->
                        <div x-show="['master_guru', 'master_kelas', 'master_mapel', 'user'].includes(currentTab)" class="overflow-x-auto bg-white/20 backdrop-blur-md rounded-xl border border-white/40 shadow-inner">
                            <table class="min-w-full text-sm border-collapse">
                                <thead class="bg-white/40 border-b border-white/50">
                                    <tr>
                                        <th x-show="currentTab !== 'user'" class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Informasi Utama</th>
                                        <th x-show="currentTab !== 'user'" class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Detail Tambahan</th>
                                        <th x-show="currentTab === 'user'" class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Nama & Username</th>
                                        <th x-show="currentTab === 'user'" class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Password</th>
                                        <th x-show="currentTab === 'user'" class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Role & Kelas</th>
                                        <th class="px-4 py-3 text-right font-black text-gray-900 uppercase drop-shadow-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/30">
                                    <template x-for="item in getActiveDataList()" :key="item.id">
                                        <tr class="hover:bg-white/50 transition">
                                            <td x-show="currentTab !== 'user'" class="px-4 py-4 whitespace-nowrap">
                                                <div class="font-black text-gray-900 text-base drop-shadow-sm" x-text="item.col1"></div>
                                            </td>
                                            <td x-show="currentTab !== 'user'" class="px-4 py-4 whitespace-nowrap font-bold text-gray-800" x-text="item.col2"></td>
                                            <td x-show="currentTab === 'user'" class="px-4 py-4 whitespace-nowrap">
                                                <div class="font-black text-gray-900 drop-shadow-sm" x-text="item.nama"></div>
                                                <div class="text-xs text-gray-800 font-bold bg-white/50 inline-block px-2 py-0.5 rounded-md mt-1 border border-white/60" x-text="item.username"></div>
                                            </td>
                                            <td x-show="currentTab === 'user'" class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-gray-800 tracking-widest bg-white/40 px-2 py-1 rounded-md border border-white/60" x-text="item.showPassword ? item.password : '........'"></span>
                                                    <button @click="item.showPassword = !item.showPassword" class="text-gray-600 hover:text-purple-600 transition p-1 bg-white/50 rounded shadow-sm border border-white/60">
                                                        <i class="fa-solid" :class="item.showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td x-show="currentTab === 'user'" class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 text-[11px] font-black uppercase rounded-lg shadow-sm border border-white/60"
                                                    :class="{'bg-purple-200/80 text-purple-900': item.role === 'admin', 'bg-blue-200/80 text-blue-900': item.role === 'guru', 'bg-emerald-200/80 text-emerald-900': item.role === 'kmkelas', 'bg-orange-200/80 text-orange-900': item.role === 'piket'}"
                                                    x-text="item.role"></span>
                                                <span x-show="item.role === 'kmkelas'" class="ml-2 px-2 py-1 bg-white/70 border border-white/80 text-gray-800 rounded-md font-black text-[10px] shadow-sm" x-text="`Kelas: ${item.kelas_km}`"></span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button @click="openModal('edit', item)" class="w-8 h-8 rounded-full bg-blue-100/80 text-blue-800 border border-white/60 hover:bg-blue-600 hover:text-white mr-2 transition backdrop-blur-sm shadow-sm"><i class="fa-solid fa-pen"></i></button>
                                                <button @click="confirmDelete(item)" class="w-8 h-8 rounded-full bg-red-100/80 text-red-800 border border-white/60 hover:bg-red-600 hover:text-white transition backdrop-blur-sm shadow-sm"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- SUB-TAB: MASTER SISWA -->
                        <div x-show="currentTab === 'master_siswa'" class="overflow-x-auto bg-white/20 backdrop-blur-md rounded-xl border border-white/40 shadow-inner">
                            <table class="min-w-full text-sm border-collapse">
                                <thead class="bg-white/40 border-b border-white/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">NIS / NISN</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Nama Lengkap Siswa</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Kelas</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Agama</th>
                                        <th class="px-4 py-3 text-right font-black text-gray-900 uppercase drop-shadow-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/30">
                                    <template x-for="item in masterSiswa" :key="item.id">
                                        <tr class="hover:bg-white/50 transition">
                                            <td class="px-4 py-4 whitespace-nowrap font-black text-gray-900 drop-shadow-sm" x-text="item.col1"></td>
                                            <td class="px-4 py-4 whitespace-nowrap font-bold text-gray-800" x-text="item.col2"></td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 bg-white/70 border border-white/80 text-purple-900 rounded-md font-black text-xs shadow-sm" x-text="item.col3"></span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap font-bold text-gray-800" x-text="item.col4 || '-'"></td>
                                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button @click="openModal('edit', item)" class="w-8 h-8 rounded-full bg-blue-100/80 text-blue-800 border border-white/60 hover:bg-blue-600 hover:text-white mr-2 transition backdrop-blur-sm shadow-sm"><i class="fa-solid fa-pen"></i></button>
                                                <button @click="confirmDelete(item)" class="w-8 h-8 rounded-full bg-red-100/80 text-red-800 border border-white/60 hover:bg-red-600 hover:text-white transition backdrop-blur-sm shadow-sm"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- SUB-TAB: JADWAL PELAJARAN -->
                        <div x-show="currentTab === 'jadwal'" class="overflow-x-auto bg-white/20 backdrop-blur-md rounded-xl border border-white/40 shadow-inner">
                            <table class="min-w-full text-sm">
                                <thead class="bg-white/40 border-b border-white/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Hari & Waktu</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Kelas & Mapel</th>
                                        <th class="px-4 py-3 text-left font-black text-gray-900 uppercase drop-shadow-sm">Guru Pengampu</th>
                                        <th class="px-4 py-3 text-right font-black text-gray-900 uppercase drop-shadow-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/30">
                                    <template x-for="jadwal in uploadedSchedules" :key="jadwal.id">
                                        <tr class="hover:bg-white/50 transition">
                                            <td class="px-4 py-3">
                                                <span class="font-black text-gray-900 drop-shadow-sm" x-text="jadwal.hari"></span><br>
                                                <span class="text-xs text-gray-800 font-bold bg-white/50 inline-block px-2 py-0.5 rounded mt-1 border border-white/60" x-text="`Jam Ke-${jadwal.jamke} (${jadwal.waktu})`"></span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 bg-white/70 border border-white/80 text-purple-900 rounded-md font-black text-xs shadow-sm" x-text="jadwal.kelas"></span><br>
                                                <span class="font-black text-gray-900 text-sm mt-2 block drop-shadow-sm" x-text="jadwal.mapel"></span>
                                            </td>
                                            <td class="px-4 py-3 font-bold text-gray-800" x-text="jadwal.guru"></td>
                                            <td class="px-4 py-3 text-right font-medium">
                                                <button @click="openModal('edit', jadwal)" class="w-8 h-8 rounded-full bg-blue-100/80 text-blue-800 border border-white/60 hover:bg-blue-600 hover:text-white mr-2 transition backdrop-blur-sm shadow-sm"><i class="fa-solid fa-pen"></i></button>
                                                <button @click="confirmDelete(jadwal)" class="w-8 h-8 rounded-full bg-red-100/80 text-red-800 border border-white/60 hover:bg-red-600 hover:text-white transition backdrop-blur-sm shadow-sm"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Form (Input/Edit) -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-md" x-cloak>
        <div class="glass-panel rounded-3xl w-full max-w-md overflow-hidden mx-4 transform transition-all border border-white/70 shadow-2xl" @click.away="showModal = false">
            <div class="px-6 py-4 border-b border-white/50 bg-white/40 flex justify-between items-center">
                <h3 class="font-black text-lg text-gray-900 drop-shadow-sm" x-text="modalMode === 'koreksi_siswa' ? getModalTitle() : ((modalMode === 'tambah' ? 'Tambah ' : 'Edit ') + getModalTitle())"></h3>
                <button @click="showModal = false" class="text-gray-600 hover:text-red-600 transition"><i class="fa-solid fa-times text-xl"></i></button>
            </div>
            
            <div class="p-6 max-h-[70vh] overflow-y-auto bg-white/20">
                <template x-if="modalMode === 'koreksi_siswa'">
                    <div class="space-y-4">
                        <div class="bg-blue-50/50 p-3 rounded-lg border border-blue-200">
                            <p class="text-xs font-black text-gray-600 uppercase">Siswa</p>
                            <p class="font-black text-lg text-gray-900" x-text="formData.nama"></p>
                            <p class="text-sm font-bold text-gray-700" x-text="`NIS: ${formData.nis} | Kelas: ${formData.kelas}`"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Status Kehadiran</label>
                            <select x-model="formData.status" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900">
                                <option value="Hadir">Hadir</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpa">Alpa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Catatan</label>
                            <input x-model="formData.catatan" type="text" placeholder="Berikan catatan bila perlu..." class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900">
                        </div>
                    </div>
                </template>

                <template x-if="!['user', 'jadwal'].includes(currentTab) && modalMode !== 'koreksi_siswa'">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm" x-text="getCol1Label()"></label>
                            <input x-model="formData.col1" type="text" class="w-full px-4 py-2.5 border border-white/60 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm" x-text="getCol2Label()"></label>
                            <input x-model="formData.col2" type="text" class="w-full px-4 py-2.5 border border-white/60 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900">
                        </div>
                        <template x-if="currentTab === 'master_siswa'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Pilih Kelas</label>
                                    <select x-model="formData.col3" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        <template x-for="k in masterKelas"><option :value="k.col1" x-text="k.col1"></option></template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Agama</label>
                                    <select x-model="formData.col4" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900" required>
                                        <option value="">-- Pilih Agama --</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="currentTab === 'user' && modalMode !== 'koreksi_siswa'">
                    <div class="space-y-4">
                        <div><label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Nama Lengkap</label><input x-model="formData.nama" type="text" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900"></div>
                        <div><label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Username</label><input x-model="formData.username" type="text" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900"></div>
                        <div><label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Password</label><input x-model="formData.password" type="text" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900"></div>
                        <div>
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Role</label>
                            <select x-model="formData.role" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900">
                                <option value="admin">Administrator</option>
                                <option value="guru">Guru</option>
                                <option value="kmkelas">KM Kelas</option>
                                <option value="piket">Piket</option>
                            </select>
                        </div>
                        <div x-show="formData.role === 'kmkelas'">
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Ditugaskan untuk Kelas</label>
                            <select x-model="formData.kelas_km" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900">
                                <option value="">-- Pilih Kelas --</option>
                                <template x-for="k in masterKelas"><option :value="k.col1" x-text="k.col1"></option></template>
                            </select>
                        </div>
                    </div>
                </template>

                <template x-if="currentTab === 'jadwal' && modalMode !== 'koreksi_siswa'">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Hari</label>
                            <select x-model="formData.hari" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900">
                                <option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option><option value="Kamis">Kamis</option><option value="Jumat">Jumat</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Jam Ke</label><input x-model="formData.jamKe" type="number" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900"></div>
                            <div><label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Waktu</label><input x-model="formData.waktu" type="text" placeholder="07:00 - 08:30" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Kelas</label>
                            <select x-model="formData.kelas" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900">
                                <option value="">Pilih Kelas...</option>
                                <template x-for="k in masterKelas"><option :value="k.col1" x-text="k.col1"></option></template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Mata Pelajaran</label>
                            <select x-model="formData.mapel" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900">
                                <option value="">Pilih Mata Pelajaran...</option>
                                <template x-for="m in masterMapel"><option :value="m.col1" x-text="m.col1"></option></template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Guru Pengampu</label>
                            <select x-model="formData.guru" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900">
                                <option value="">Pilih Guru...</option>
                                <template x-for="g in masterGuru"><option :value="g.col1" x-text="g.col1"></option></template>
                            </select>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="px-6 py-4 border-t border-white/50 bg-white/40 flex justify-end gap-3">
                <button @click="showModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-800 bg-white/50 border border-white/60 backdrop-blur-sm hover:bg-white/80 rounded-xl transition shadow-sm">Batal</button>
                <button @click="saveData()" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600/90 to-purple-600/90 border border-purple-400 backdrop-blur-sm hover:from-blue-700 hover:to-purple-700 rounded-xl shadow-md transition transform hover:scale-105">
                    Simpan <i x-show="isProcessingData" class="fa-solid fa-spinner fa-spin ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Data -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-lg" x-cloak>
        <div class="glass-panel rounded-3xl w-full max-w-sm overflow-hidden mx-4 transform transition-all border border-white/70 shadow-2xl" @click.away="showDeleteModal = false">
            <div class="p-6 text-center bg-white/20">
                <i class="fa-solid fa-triangle-exclamation text-5xl text-red-500 mb-4 drop-shadow-md"></i>
                <h3 class="font-black text-xl text-gray-900 mb-2 drop-shadow-sm">Konfirmasi Hapus</h3>
                <p class="text-sm font-bold text-gray-700 mb-6">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex justify-center gap-3">
                    <button @click="showDeleteModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-800 bg-white/50 border border-white/60 backdrop-blur-sm hover:bg-white/80 rounded-xl transition shadow-sm">Batal</button>
                    <button @click="deleteItem()" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-red-500/90 to-pink-600/90 border border-red-400 backdrop-blur-sm hover:from-red-600 hover:to-pink-700 rounded-xl shadow-md transition transform hover:scale-105">Ya, Hapus!</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semua Data -->
    <div x-show="showDeleteAllModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-lg" x-cloak>
        <div class="glass-panel rounded-3xl w-full max-w-sm overflow-hidden mx-4 transform transition-all border border-white/70 shadow-2xl" @click.away="showDeleteAllModal = false">
            <div class="p-6 text-center bg-white/20">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4 border border-red-200">
                    <i class="fa-solid fa-skull-crossbones text-3xl text-red-600 drop-shadow-md"></i>
                </div>
                <h3 class="font-black text-xl text-gray-900 mb-2 drop-shadow-sm">HAPUS SEMUA DATA?</h3>
                <p class="text-sm font-bold text-gray-700 mb-6 bg-red-50/50 p-3 rounded-xl border border-red-200 text-left">
                    Tindakan ini akan <b>MENGHAPUS SELURUH DATA</b> yang ada pada tabel <b class="uppercase" x-text="currentTab.replace('_', ' ')"></b>.
                    <br><br>Apakah Anda sangat yakin?
                </p>
                <div class="flex justify-center gap-3">
                    <button @click="showDeleteAllModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-800 bg-white/50 border border-white/60 backdrop-blur-sm hover:bg-white/80 rounded-xl transition shadow-sm">Batal</button>
                    <button @click="executeDeleteAll()" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-red-600/90 to-rose-700/90 border border-red-500 backdrop-blur-sm hover:from-red-700 hover:to-rose-800 rounded-xl shadow-lg transition transform hover:scale-105">YA, HAPUS SEMUANYA!</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Transfer Kelas Masal -->
    <div x-show="showTransferModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-lg" x-cloak>
        <div class="glass-panel rounded-3xl w-full max-w-md overflow-hidden mx-4 transform transition-all border border-white/70 shadow-2xl" @click.away="showTransferModal = false">
            <div class="px-6 py-4 border-b border-white/50 bg-white/40 flex justify-between items-center">
                <h3 class="font-black text-lg text-gray-900 drop-shadow-sm">Transfer Kelas Masal</h3>
                <button @click="showTransferModal = false" class="text-gray-600 hover:text-red-600 transition"><i class="fa-solid fa-times text-xl"></i></button>
            </div>
            
            <div class="p-6 bg-white/20 space-y-4">
                <div class="bg-orange-50/70 p-4 rounded-xl border border-orange-200 text-sm font-bold text-orange-900 shadow-sm">
                    <i class="fa-solid fa-circle-info mr-1"></i> Pindahkan semua siswa dari satu kelas ke kelas lain secara masal.
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-600 mb-1 uppercase tracking-wider">Kelas Asal (Sumber)</label>
                    <select x-model="transferSource" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/70 backdrop-blur outline-none focus:ring-2 focus:ring-orange-400 font-bold text-gray-900 text-sm transition shadow-sm">
                        <option value="">-- Pilih Kelas Asal --</option>
                        <template x-for="k in masterKelas"><option :value="k.col1" x-text="k.col1"></option></template>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-600 mb-1 uppercase tracking-wider">Kelas Tujuan</label>
                    <select x-model="transferTarget" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/70 backdrop-blur outline-none focus:ring-2 focus:ring-orange-400 font-bold text-gray-900 text-sm transition shadow-sm">
                        <option value="">-- Pilih Kelas Tujuan --</option>
                        <template x-for="k in masterKelas"><option :value="k.col1" x-text="k.col1"></option></template>
                    </select>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-white/50 bg-white/40 flex justify-end gap-3">
                <button @click="showTransferModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-800 bg-white/50 border border-white/60 backdrop-blur-sm hover:bg-white/80 rounded-xl transition shadow-sm">Batal</button>
                <button @click="executeTransferClass()" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-orange-500/90 to-red-500/90 border border-orange-400 backdrop-blur-sm hover:from-orange-600 hover:to-red-600 rounded-xl shadow-md transition transform hover:scale-105">Proses Pindah</button>
            </div>
        </div>
    </div>

    <!-- Modal Ganti Password -->
    <div x-show="showPasswordModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-lg" x-cloak>
        <div class="glass-panel rounded-3xl w-full max-w-md overflow-hidden mx-4 transform transition-all border border-white/70 shadow-2xl" @click.away="showPasswordModal = false">
            <div class="px-6 py-4 border-b border-white/50 bg-white/40 flex justify-between items-center">
                <h3 class="font-black text-lg text-gray-900 drop-shadow-sm">Ganti Password</h3>
                <button @click="showPasswordModal = false" class="text-gray-600 hover:text-red-600 transition"><i class="fa-solid fa-times text-xl"></i></button>
            </div>
            
            <div class="p-6 bg-white/20 space-y-4">
                <div>
                    <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Password Lama</label>
                    <input type="password" x-model="passwordForm.oldPassword" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900" required>
                </div>
                <div>
                    <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Password Baru</label>
                    <input type="password" x-model="passwordForm.newPassword" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900" required>
                </div>
                <div>
                    <label class="block text-sm font-black text-gray-900 mb-1 drop-shadow-sm">Konfirmasi Password Baru</label>
                    <input type="password" x-model="passwordForm.confirmPassword" class="w-full px-4 py-2.5 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900" required>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-white/50 bg-white/40 flex justify-end gap-3">
                <button @click="showPasswordModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-800 bg-white/50 border border-white/60 backdrop-blur-sm hover:bg-white/80 rounded-xl transition shadow-sm">Batal</button>
                <button @click="ubahPassword()" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-purple-500/90 to-pink-500/90 border border-purple-400 backdrop-blur-sm hover:from-purple-600 hover:to-pink-600 rounded-xl shadow-md transition transform hover:scale-105">Simpan Password</button>
            </div>
        </div>
    </div>

    <!-- Modal Input Tugas -->
    <div x-show="showTugasModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-lg" x-cloak>
        <div class="glass-panel rounded-3xl w-full max-w-md overflow-hidden mx-4 transform transition-all border border-white/70 shadow-2xl" @click.away="showTugasModal = false">
            <div class="px-6 py-4 border-b border-white/50 bg-white/40 flex justify-between items-center">
                <h3 class="font-black text-lg text-gray-900 drop-shadow-sm">Berikan Tugas</h3>
                <button @click="showTugasModal = false" class="text-gray-600 hover:text-red-600 transition"><i class="fa-solid fa-times text-xl"></i></button>
            </div>
            
            <div class="p-6 bg-white/20">
                <label class="block text-sm font-black text-gray-900 mb-2 drop-shadow-sm">Deskripsi Tugas</label>
                <textarea x-model="tugasForm.deskripsi" rows="4" placeholder="Tuliskan instruksi tugas atau halaman buku yang harus dikerjakan siswa..." class="w-full px-4 py-3 border border-white/60 rounded-xl bg-white/60 backdrop-blur-sm outline-none transition font-bold text-gray-900 resize-none"></textarea>
            </div>
            
            <div class="px-6 py-4 border-t border-white/50 bg-white/40 flex justify-end gap-3">
                <button @click="showTugasModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-800 bg-white/50 border border-white/60 backdrop-blur-sm hover:bg-white/80 rounded-xl transition shadow-sm">Batal</button>
                <button @click="submitTugas()" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-yellow-500/90 to-orange-500/90 border border-yellow-400 backdrop-blur-sm hover:from-yellow-600 hover:to-orange-600 rounded-xl shadow-md transition transform hover:scale-105">Simpan Tugas</button>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Detail Tugas -->
    <div x-show="showTugasDetailModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-lg" x-cloak>
        <div class="glass-panel rounded-3xl w-full max-w-md overflow-hidden mx-4 transform transition-all border border-white/70 shadow-2xl" @click.away="showTugasDetailModal = false">
            <div class="px-6 py-4 border-b border-white/50 bg-white/40 flex justify-between items-center">
                <h3 class="font-black text-lg text-gray-900 drop-shadow-sm">Detail Tugas</h3>
                <button @click="showTugasDetailModal = false" class="text-gray-600 hover:text-red-600 transition"><i class="fa-solid fa-times text-xl"></i></button>
            </div>
            
            <div class="p-6 bg-white/20 space-y-4">
                <div class="bg-yellow-50/80 p-4 rounded-xl border border-yellow-200 shadow-sm">
                    <p class="text-[10px] font-black text-yellow-800 uppercase tracking-wider mb-1">Mata Pelajaran</p>
                    <p class="font-black text-gray-900" x-text="tugasDetailData?.mapel"></p>
                    <p class="text-xs font-bold text-gray-700 mt-1" x-text="`${tugasDetailData?.guru} | Kelas ${tugasDetailData?.kelas}`"></p>
                </div>
                
                <div>
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-wider mb-1">Instruksi / Deskripsi Tugas:</p>
                    <div class="bg-white/60 p-4 rounded-xl border border-white/60 font-bold text-gray-800 shadow-inner whitespace-pre-wrap min-h-[100px]" x-text="tugasDetailData?.deskripsiTugas"></div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-white/50 bg-white/40 flex justify-end">
                <button @click="showTugasDetailModal = false" class="px-6 py-2.5 text-sm font-bold text-white bg-gray-800 hover:bg-gray-900 rounded-xl transition shadow-md">Tutup</button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div class="toast-container fixed bottom-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="px-5 py-3 rounded-2xl shadow-lg border backdrop-blur-xl flex items-start gap-3 transform transition-all duration-300 animate-[slideInRight_0.3s_ease-out] pointer-events-auto"
                 :class="{
                    'bg-blue-500/90 border-blue-400 text-white': toast.type === 'info',
                    'bg-green-500/90 border-green-400 text-white': toast.type === 'success',
                    'bg-red-500/90 border-red-400 text-white': toast.type === 'error',
                    'bg-yellow-500/90 border-yellow-400 text-white': toast.type === 'warning'
                 }">
                <div class="mt-0.5">
                    <i class="fa-solid" :class="{
                        'fa-circle-info': toast.type === 'info',
                        'fa-circle-check': toast.type === 'success',
                        'fa-circle-exclamation': toast.type === 'error',
                        'fa-triangle-exclamation': toast.type === 'warning'
                    }"></i>
                </div>
                <div>
                    <h4 class="font-black text-sm drop-shadow-sm" x-text="toast.title"></h4>
                    <p class="text-xs font-bold opacity-90 mt-0.5" x-text="toast.message"></p>
                </div>
                <button @click="removeToast(toast.id)" class="ml-auto mt-0.5 opacity-70 hover:opacity-100 transition"><i class="fa-solid fa-times"></i></button>
            </div>
        </template>
    </div>

    <style>
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</body>
</html>