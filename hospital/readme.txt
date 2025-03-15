$host = 'localhost';
$user = 'root';
$password = ''';
$database = 'hospital_sistemi';

This is the login information of our database.

Doctor Panel


In this project, we have a registration page for the doctor and when we register, it directs us to our login page.
When we log in with our doctor account on this page, we are greeted by the doctor panel and here we first need to add a secretary.
Secretaries check our patients' information and appointments for us. We can both edit and delete secretaries.
We also list our patients in our doctor panel. Here we can view past reports and examination histories.
Finally, we can see our appointments and in this appointments section, we can add a time that we work on a day we want and make an appointment in that time interval.
we may not get it.


Secretary Panel


We log in to the system with our login information we receive from our doctor and our secretary panel welcomes us.
Here we have patient management, appointment scheduling and reports.
In patient management, we can add, edit, delete our doctor's patients, and at the same time we upload
We can update and delete reports.
In the appointment scheduling section, we create appointment times for our patients, taking into account the working hours of our doctor.
In the reports section, our report_yukle.php is running and we upload the report about the patient we have selected to the system and these reports
It is kept in our folder named /reports.



All this system works all fine with the database. Have a good day!



$host = 'localhost';
$kullanici = 'root';
$sifre = '';
$veritabani = 'hastane_sistemi';

Bu veritabanimızın giriş bilgileridir.

Doktor Paneli


Bu projemizde doktor için kayıt sayfamız bulunuyor ve kayıt olduğumuzda giriş sayfamıza yönlendiriyor.
Bu sayfada doktor hesabımız ile giriş yaptığımızda bizi doktor paneli karşılıyor ve burada öncelikle sekreter eklememiz gerekiyor.
Sekreterler bizim için hastalarımızın bilgilerini , randevularını kontrol eder. Sekreterleri hem düzenleyip hem de silebiliyoruz.
Aynı zamanda doktor panelimizde hastalarımızı listeliyoruz. Burada geçmiş raporlarını ve muayenen geçmişlerini görüntüleyebiliyoruz.
Son olarak randevularımızı görebiliyoruz ve bu randevular kısmında istediğimiz bir güne işimiz olan bir saati ekleyip o saat aralığında randevu
almayabiliyoruz.


Sekreter Paneli


Doktorumuzdan aldığımız giriş bilgilerimiz ile sisteme giriş yapıyoruz ve bizi sekreter panelimiz karşılıyor.
burada hasta yönetimi, randevu planlama ve raporlar kısmımız bulunuyor.
Hasta yönetiminde doktorumuzun hastalarını ekleme, düzenleme, silme işlemi ve aynı zamanda hastalarla alakalı yüklediğimiz
raporları güncelleme ve silme işlemleri yapabiliyoruz.
Randevu planlama kısmında doktorumuzun işi olan saatlerini de göz önüne alarak hastalarımız için randevu saatleri oluşturuyoruz.
Raporlar kısmında ise rapor_yukle.php miz çalışıyor ve seçtiğimiz hasta ile ilgili olan raporu sisteme yüklüyoruz ve bu raporlar
/raporlar isimli klasörümüzde tutuluyor.



Tüm bu sistem veritabani ile senkronize olarak çalışıyor. İyi günler.


Furkan Eraslan 22000459