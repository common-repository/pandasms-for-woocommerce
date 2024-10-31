=== PandaSMS for WooCommerce (Siparişler için SMS göndermek, Yöneticiye SMS bildirimi, Intense Kargo eklentileriyle uyumlu) ===
Contributors: intenseyazilim
Tags: pandasms, panda sms, toplu sms, sipariş sms, woocommerce sms, wordpress sms, Türkiye sms, sipariş sms, kargo takip, kargo sms, yurtiçi kargo, mng kargo, aras kargo, ups kargo, kargo entegrasyon, ups, il ilçe, intense
Requires at least: 5
Tested up to: 6.4
Stable tag: 4.2.3
Requires PHP: 7.0

PandaSMS WooCommerce eklentisiyle,  tüm WooCommerce sipariş durum değişikliklerinde ( özel durumlar dahil ) SMS bildirimi yapabilirsiniz.
Eklentiyi kullanabilmek için pandasms.com abonesi olmanız gerekmektedir.
Abonelik için 0376 213 33 24 nolu telefondan veya info@pandasms.com e-posta adresinden destek alabilirsiniz.

== İlave Uyumluluklar ==
* Intense Kargo Takip Eklentisi for WooCommerce (kargoya verildiğinde, sipariş tamamlandığında)

* Intense Yurtiçi Kargo Entegrasyonu for WooCommerce (kargo dağıtıma çıktığında, kargo teslim edildiğinde, kargo teslim edilemediğinde (hava şartları, alıcı adreste bulunamad vb.), kargo şubeye ulaştığında vb.)

* Intense MNG Kargo Entegrasyonu for WooCommerce (kargoya verildiğinde, sipariş tamamlandığında)

* Intense UPS Kargo Entegrasyonu for WooCommerce (kargoya verildiğinde, sipariş tamamlandığında)

* Intense Aras Kargo Entegrasyonu for WooCommerce (kargoya verildiğinde, sipariş tamamlandığında)

* Intense Sürat Kargo Entegrasyonu for WooCommerce (kargoya verildiğinde, sipariş tamamlandığında)

* Intense eklentileri hakkında detaylı bilgi için: [intense.com.tr](https://intense.com.tr/)

== Installation ==
1. Eklentiyi aktifleştirin, pandasms.com'dan hesabınıza giriş yapınız, ürettiğiniz API keyi, kullanıcı adı ve şifrenizi eklenti ayarlarına giriniz.

2. Eklentide bulunan mesaj şablonlarından gönderim formatlarını düzenlemeniz ve istediğiniz gönderimleri aktif etmeniz yeterlidir.


* Bilgilendirme
PandaSMS bir toplu SMS gönderim servisidir. WooCommerce üzerinden yapılacak SMS gönderimleriniz, https://www.pandasms.com altyapısı üzerinden telekom operatörlerine iletilmektedir. PandaSMS WooCommerce eklentisi ücretsizdir. Eklentiyi kullanabilmek için PandaSMS abonesi olmanız gerekmektedir. SMS kredi alımlarınızı https://pandasms.com adresinden hesabınıza giriş yaparak yapabilirsiniz.

== Frequently Asked Questions ==

= Nasıl Abone Olabilirim? =

https://www.pandasms.com/abonelik adresinde yer alan evrakları info@pandasms.com'a iletmeniz gerekmektedir. Ardından ekibimiz onayıyla kargoya teslim ediniz.

= Özel Oluşturduğum Sipariş Durumlarında SMS Gönderebilir Miyim? =
Evet, WooCommerce için oluşturduğunuz özel sipariş durumları ( Paketlendi, Hazırlanıyor vb. ) için SMS gönderimi yapabilirsiniz. ( Özel sipariş durumu oluşturmak için harici eklentiler kullanabilirsiniz veya functions.php dosyasınıza gerekli snippet ekleyerek özel sipariş durumu oluşturabilirsiniz.

= Eklenti Ücretsiz Mi? =
Eklentimiz ücretsizdir. PandaSMS'e ücretsiz olarak abone olabilirsiniz, WooCommerce eklentimizi kullandığınızda pandasms.com hesabınızdan kredi düşülecektir.

= Abonelik Süreci Ne Kadar Sürede Sonuçlanır? =
Genellikle abonelik işlemleriniz aynı gün içerisinde tamamlanmaktadır.

=Kargo Bilgilerini Nasıl Gönderebilirim?=
Eklentimiz Intense Kargo Eklentileri ile uyumlu olarak çalışmaktadır, bu özelliği kullanabilmek için www.intense.com.tr adresinden gerekli eklentiyi satın almış olmanız gerekmektedir.

=Gönderilecek SMS'ler Başlıklı Mı?
Evet, tüm göndereceğiniz SMS'ler uygunluk kontrolü yapıldıktan sonra firma adınız, marka adınız ile yapılmaktadır. Dilerseniz 0850 ile başlayan numara ile de gönderim yapabilirsiniz.

= Geliştirdiğim WooCommerce eklentisine PandaSMS'i entegre edebilir miyim? =
Evet, PandaSMS olarak yazılım geliştiriciler için WooCommerce eklentimize entegre olabilmenize imkan sağlıyoruz, dökümantasyon desteği için info@pandasms.com ile iletişime geçebilirsiniz.

= Öneri ve Görüşler =
Taleplerinizi info@pandasms.com e-posta adresine iletebilirsiniz.

=PandaSMS Hakkında=
PandaSMS 2014 yılında hizmet vermeye başlamıştır. Çok sayıda müşterisine entegrasyon odaklı SMS hizmeti vermektedir.


== Screenshots ==
1. Sipariş detayından sms mesajlarını görüntüleyebilme
2. SMS metinlerini düzenleyebilme

== Changelog ==
= 4.2.3 = 14/01/2024 =
* Eşzamanlı SMS gönderim problemi giderildi.
* Pandasms logosu değiştirildi.

= 4.2.0 = 01/12/2023 =
* Tekrarlı SMS gönderiminin engellenmesi özelliği eklendi (opsiyonel fakat varsayılan olarak aktiftir.)
* Güncelleme uyarısının gerekmediği durumlarda da gösterilmesi problemi giderildi.

= 4.1.0 = 01/12/2023 =
* HPOS Uyumluluğu

= 4.0.1 = 01/12/2023 =
* Admin PHP uyarısı düzeltildi.

= 4.0.0 = 01/12/2023 =
* PTT Kargo için takip URL oluşturma desteği eklendi
* PandaSMS API V10 desteği eklendi.
* Reddedilen SMS'lerin kuyruktan iptali özelliği eklendi.

= 3.18.3 = 21/07/2023 =
* İyileştirme: SMS gönderim test çıktısının iyileştirilmesi

= 3.18.2 = 20/07/2023 =
* Hata Giderme: 3. parti uyumluluklarıyla ilgili bir hata giderme. (pandasms_wc_siparis_bildirimi fonksiyonunda aranan şablon için varlık kontrolü yapılması)

= 3.18.1 = 22/09/2022 =
* Hata Giderme: PHP8'de ortaya çıkan PHP uyarısı giderilmiştir.

= 3.18.0 - 02/08/2022 =
* Hata Giderme: Bazı PHP uyarıları giderildi.
* Hata Giderme: Gönderim kuyruğu ekranında, numara sayısı sütunundaki verinin hatalı görünmesi problemi giderildi. (v3.18.0 sonrası yapılacak gönderimler için geçerlidir.)
* Teknik iyileştirmeler

= 3.17.1 - 27/10/2021 =
* hata giderme: raporlar ekranındaki sayfalama sorunu giderildi.
* hata giderme: raporlar sayfasındaki "bekleyen gönderimleri iptal et" özelliğinin çalışmaması problemi düzeltildi.

= 3.17.0 - 17/06/2021 =
* özellik: kalan sms kredisini panel üzerinden görüntüleyebilme ekranı eklendi.
* özellik: sms gönderim test ekranı eklendi.

= 3.16.0 - 08/04/2021 =
* refactor: SMS gönderim timeout süresi 5sn'den 15sn'ye yüksetildi.

= 3.15.0-beta - 12/03/2021 =
* fix: [performans iyileştirmesi] sms gönderimi yapılırken, kuyruk tetikleme işleminde tüm kuyruğun gönderim tetiklemesi yerine sadece ilgili sms'in kuyruktan tetiklenmesi sağlandı.

= 3.14.0 - 29/09/2020 =
* Bekleyen gönderimleri iptal edebilme özelliği eklendi.
* Rapor sayfasına sayfalama özelliği eklendi.

= 3.13.0 - 06/08/2020 =
* SMS Gönderme işlemi timeout 5sn olarak güncellendi.

= 3.12.0 - 02/07/2020 =
* Her şablonun opsiyonel olarak harici yönetici numaralarına kopyalanabilmesi sağlandı.
* Yeni müşteri siparişinde, istenilen yönetici numaralarının bilgilendirilebilmesi sağlandı.
* mesaj şablonlarında mesaj içeriklerinde tırnak işareti kullandığında, içeriğe istenmeyen slash eklenmesi problemi giderildi.


= 3.9.1 - 02/05/2020 =
* PandaSMS CSS kütüphanesinin; tüm WP admin link stillerini etkilemesi problemi giderildi. ( WP admin panelinde linklerin üzerine gelindiğinde beyaz olması )

= 3.9.0 - 02/05/2020 =
* Güncelleştirme öncesi versiyonu 3.0.0 altında olan kullanıcılara otomatik db güncellemesi yapılmaması sağlandı.
* Sipariş bildirim metinlerinde; SiparisId kısa kod açıklaması güncelendi.
* Admin ekranında gösterilen uyarıyla; manuel veritabanı güncellemesi başlatılabilmesi sağlandı.

= 3.8.3 - 01/05/2020 =
* Kayıtlı gönderim şablonu bulunmayan kullanıcılara admin ekranında bildirim gösterilmesi
* Tasarımsal iyileştirmeler

= 3.8.2 - 01/05/2020 =
* WooCommerce tüm sipariş durumlarında SMS gönderim özelliği eklendi.
* SMS Şablonlarına WooCommerce siparişiyle ilgili çok detaylı kısa kodlar eklendi.
* Harici WooCommerce eklentilerinin PandaSMS for WooCommerce eklentisine entegre edilebilmesi sağlandı. ( Geliştiriciler için dökümantasyon yakında yayınlacaktır. )
* 3.0.0 öncesi versiyonlarda oluşturulmuş SMS şablonlarının, versiyon güncellemesiyle silinmesi sağlandı.


= 2.1.6 - 06/12/2020 =
Readme.txt güncellendi.

= 2.1.5 - 06/12/2020 =
Readme.txt güncellendi.

= 2.1.4 - 06/12/2020 =
Ekran görüntülerinde düzeltmeler.

= 2.1.3 - 06/02/2020 =
Ekran görüntülerinde düzenlemeler

= 2.1.2 - 06/02/2020 =
Hata Giderme: Syntax

= 2.1.1 - 19/01/2020 =
Sipariş düzenleme ekranından kargo bilgileri girilirken manuel olarak sipariş durumu kargoya verildi yapıldığında sms bildirim metninde kargo bilgilerinin boş gönderilmesi hatası giderildi.

= 2.1.0 - 19/01/2020 =
Bazı düzeltmeler ve güvenlik önlemleri

= 2.0.5 - 19/11/2019 =
Eklenti yayımlandı.

== Upgrade Notice ==

= 3.8.2 =
Büyük Güncelleme. SMS metinlerine çok sayıda yeni kısa kod eklendi, bu nedenle bir defaya mahsus olarak geçmiş SMS şablonlarınız silinecektir, eklenti ayarlarından yeni şablonlarınızı kolayca oluşturabilirsiniz.