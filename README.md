## Slim Api + React Full Stack APP
Merhaba, uygulamamızın ana sayfası açıldığında bu şekilde gözüküyor.
<img src="https://i.hizliresim.com/k2prqev.png" >

## Backend 
İlk olarak backendimizde public altında index.php'yi komut satırından çalıştırdığımızda böyle bir çıktı alıyoruz.
<img src="https://i.hizliresim.com/e0geacy.png" >
İstek attığımız API'den gelen veri daha önceden veritabanımıza eklendiyse eklenmesin diye bir kontrol koydum.
Eğer aynı id'ye sahip gönderiler gelirse mevcut olan veri ve eklenen veriyi yazdırıyor. 2 API için de bu kontrolü sağlıyorum.
Controller ve Modal sistemi kullanarak kodların daha düzenli olmasını sağladım.

## Frontend
Tabloda silme işlemine modal ekleyerek kullanıcı hatalarını bir nebze olarak engelledim. Silme işlemi başarılı olunca toast yazdırıyoruz.
Çok fazla veri bulunduğu için de pagination sistemi ekleyerek tablonun daha düzenli gözükmesini sağladım.
### Modal
<img src="https://i.hizliresim.com/g8k00vl.png" >
### Toast
<img src="https://i.hizliresim.com/fgrwfje.png" >
### Pagination
<img src="https://i.hizliresim.com/c09u8r0.png" >


