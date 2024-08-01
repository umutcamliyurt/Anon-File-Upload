<?php
require 'set_headers.php';

$jsFilePaths = [
    './js/languageSwitcher.js'
];

$cssFilePaths = [
    './styles/terms-of-use.css'
];

$elements = setSecurityHeadersAndGenerateElements($jsFilePaths, $cssFilePaths);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Use</title>
    <?php insertElementsIntoHead($elements); ?>
</head>

<body>
    <div class="container">
        <div class="language-switcher">
            <button id="lang-en-btn">English</button> 
            <button id="lang-tr-btn">Türkçe</button>
        </div>
        <div id="lang-en" class="block">
            <h1>Terms of Use</h1>
            <p>By accessing this website or
                using the software/service provided, you agree to the following terms and
                conditions:</p>
            <h2>MIT License</h2>
            <p>The software and source code available
                on this website are licensed under the MIT License, reproduced below for your
                convenience:</p>
            <pre>
MIT License Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal in the 
Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to 
permit persons to whom the Software is furnished to do so, subject to the following 
conditions: The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software. THE SOFTWARE IS PROVIDED "AS IS", WITHOUT 
WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION 
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
            </pre>
            <h2>Prohibited Uses</h2>
            <p>Users agree not to use the service to
                upload, post, or otherwise transmit any content that:</p>
            <ul>
                <li>is illegal, harmful, threatening, abusive, harassing, defamatory,
                    vulgar, obscene, libelous, or otherwise objectionable;</li>
                <li>violates
                    any applicable local, national, or international law;</li>
                <li>infringes
                    the intellectual property rights of others;</li>
                <li>contains software
                    viruses or any other computer code, files, or programs designed to
                    interrupt, destroy, or limit the functionality of any computer software or
                    hardware.</li>
            </ul>
            <h2>User Responsibility</h2>
            <p>Users are solely responsible for the
                content they upload and must ensure that they have the legal right to upload
                such content.</p>
            <h2>Content Removal</h2>
            <p>We reserve the right to remove
                or disable access to any content that we deem to be in violation of these
                Terms of Use or applicable law. If you believe that content on this site
                infringes your rights, please notify us.</p>
            <h2>Data Privacy</h2>
            <p>Our use
                of your data is governed by our Privacy Policy. Please review our Privacy
                Policy to understand our practices.</p>
            <h2>Jurisdiction and Governing
                Law</h2>
            <p>These Terms of Use are governed by the laws of Türkiye, and any
                disputes will be resolved in the courts of Türkiye.</p>
            <h2>Indemnification</h2>
            <p>You agree to indemnify and hold harmless the owner
                of this project from any claims, damages, or legal expenses arising from your
                use of the service or your violation of these Terms of Use.</p>
            <h2>Disclaimer
                of Liability</h2>
            <p>The owner of this project is not liable for any direct,
                indirect, incidental, special, exemplary, or consequential damages, including
                but not limited to, damages for loss of profits, goodwill, use, data, or other
                intangible losses resulting from:</p>
            <ul>
                <li>the use or inability to use the service;</li>
                <li>any content obtained
                    from the service; and</li>
                <li>unauthorized access, use, or alteration of
                    your transmissions or content, whether based on warranty, contract, tort
                    (including negligence), or any other legal theory, whether or not the
                    owner has been informed of the possibility of such damage, and even if a
                    remedy set forth herein is found to have failed of its essential
                    purpose.</li>
            </ul>
            <p>Last updated: <time datetime="2024-06-24">June 24, 2024</time></p>
            <p>Users of this service are responsible for complying with all applicable
                laws and regulations.</p>
        </div>
        <div id="lang-tr" class="hidden">
            <h1>Kullanım Koşulları</h1>
            <p>Bu
                web sitesine erişerek veya sağlanan yazılım/hizmeti kullanarak, aşağıdaki
                şartları ve koşulları kabul etmiş olursunuz:</p>
            <h2>MIT Lisansı</h2>
            <p>Bu
                web sitesinde bulunan yazılım ve kaynak kodu, kolaylığınız için aşağıda
                yeniden üretilmiş olan MIT Lisansı altında lisanslanmıştır:</p>
            <pre>
MIT Lisansı İşbu yazılım ve ilişkili dokümantasyon dosyalarını ("Yazılım") elde eden her 
kişiye, Yazılım ile sınırlama olmaksızın kullanma, kopyalama, değiştirme, birleştirme, 
yayınlama, dağıtma, alt lisanslama ve/veya satma hakları dahil olmak üzere Yazılım'ı 
serbestçe kullanma izni verilmiştir, ayrıca bu kişilere Yazılım'ın sağlandığı kişilere de 
bu hakları vermelerine izin verilir, aşağıdaki koşullara tabi olarak: Yukarıdaki telif 
hakkı bildirimi ve bu izin bildirimi, Yazılım'ın tüm kopyalarına veya kayda değer 
kısımlarına dahil edilecektir. YAZILIM "OLDUĞU GİBİ", AÇIK VEYA ZIMNİ HERHANGİ BİR GARANTİ 
OLMAKSIZIN SAĞLANMAKTADIR, DAHİLİ OLMAK ÜZERE ANCAK BUNLARLA SINIRLI OLMAMAK ÜZERE 
SATILABİLİRLİK, BELİRLİ BİR AMACA UYGUNLUK VE İHLAL ETMEME GARANTİLERİ. HİÇBİR DURUMDA 
YAZARLAR VEYA TELİF HAKKI SAHİPLERİ, TALEP, HASAR VEYA DİĞER SORUMLULUKLARDAN, İSTER BİR 
SÖZLEŞME, İSTER HAKSIZ FİİL (İHMAL DAHİL) VEYA BAŞKA BİR ŞEKİLDE, YAZILIM'DAN VEYA 
YAZILIM'IN KULLANIMINDAN VEYA DİĞER İŞLEMLERDEN KAYNAKLANAN HİÇBİR DURUMDA SORUMLU 
OLMAYACAKTIR.
            </pre>
            <h2>Yasaklanmış Kullanımlar</h2>
            <p>Kullanıcılar, aşağıdaki içerikleri
                yüklemek, göndermek veya başka bir şekilde iletmek için hizmeti kullanmamayı
                kabul eder:</p>
            <ul>
                <li>yasadışı, zararlı, tehdit edici, taciz edici, iftira niteliğinde,
                    kaba, müstehcen, karalayıcı veya başka bir şekilde sakıncalı olan;</li>
                <li>uygulanabilir yerel, ulusal veya uluslararası yasaları ihlal
                    eden;</li>
                <li>başkalarının fikri mülkiyet haklarını ihlal eden;</li>
                <li>herhangi bir bilgisayar yazılımının veya donanımının işlevselliğini
                    kesintiye uğratmak, yok etmek veya sınırlamak için tasarlanmış yazılım
                    virüsleri veya diğer bilgisayar kodlarını, dosyalarını veya programlarını
                    içeren.</li>
            </ul>
            <h2>Kullanıcı Sorumluluğu</h2>
            <p>Kullanıcılar, yükledikleri içerikten
                tek başına sorumludur ve bu tür içeriği yüklemek için yasal hakka sahip
                olduklarından emin olmalıdır.</p>
            <h2>İçerik Kaldırma</h2>
            <p>Bu Kullanım
                Koşullarını veya geçerli yasaları ihlal ettiğini düşündüğümüz herhangi bir
                içeriği kaldırma veya erişimini engelleme hakkını saklı tutarız. Bu sitedeki
                içeriğin haklarınızı ihlal ettiğini düşünüyorsanız, lütfen bize bildirin.</p>
            <h2>Veri Gizliliği</h2>
            <p>Verilerinizi kullanımımız Gizlilik Politikamız
                tarafından yönetilmektedir. Uygulamalarımızı anlamak için lütfen Gizlilik
                Politikamızı inceleyin.</p>
            <h2>Yargı Yetkisi ve Geçerli Hukuk</h2>
            <p>Bu
                Kullanım Koşulları Türkiye yasalarına tabidir ve herhangi bir ihtilaf Türkiye
                mahkemelerinde çözülecektir.</p>
            <h2>Tazminat</h2>
            <p>Bu hizmeti
                kullanımınızdan veya bu Kullanım Koşullarını ihlal etmenizden kaynaklanan
                herhangi bir iddia, zarar veya yasal masraflardan bu projenin sahibini tazmin
                etmeyi ve sorumlu tutmamayı kabul edersiniz.</p>
            <h2>Sorumluluk Reddi</h2>
            <p>Bu projenin sahibi, doğrudan, dolaylı, arızi, özel, örnek teşkil eden veya
                sonuç olarak ortaya çıkan herhangi bir zarardan, kar kaybı, itibar kaybı,
                kullanım kaybı, veri kaybı veya diğer maddi olmayan kayıplardan sorumlu
                değildir, bunlarla sınırlı olmamak üzere:</p>
            <ul>
                <li>hizmeti kullanamama veya kullanma;</li>
                <li>hizmetten elde edilen
                    herhangi bir içerik; ve</li>
                <li>izin verilmemiş erişim, kullanım veya
                    iletimlerinizin veya içeriğinizin değiştirilmesi, ister garantiye, ister
                    sözleşmeye, haksız fiile (ihmal dahil) veya başka bir yasal teoriye dayalı
                    olsun, bu tür zararların olasılığı hakkında bilgilendirilmiş olup
                    olmadığına bakılmaksızın ve burada belirtilen bir çözümün esas amacına
                    ulaşmadığı tespit edilse bile.</li>
            </ul>
            <p>Son güncelleme: <time datetime="2024-06-24">24 Haziran
                    2024</time></p>
            <p>Bu hizmeti kullanan kullanıcılar, tüm geçerli yasa ve
                düzenlemelere uymaktan sorumludur.</p>
        </div>
    </div>
</body>

</html>