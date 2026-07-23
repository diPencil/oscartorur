import json
import urllib.request
import urllib.parse
import time

def translate_text(text):
    if not text.strip(): return text
    url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=ar&dt=t&q=" + urllib.parse.quote(text)
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        response = urllib.request.urlopen(req)
        res_data = json.loads(response.read().decode('utf-8'))
        translated = "".join([sentence[0] for sentence in res_data[0] if sentence[0]])
        return translated
    except Exception as e:
        print("Error translating:", text, e)
        return text

file_path = 'core/resources/lang/ar.json'

with open(file_path, 'r', encoding='utf-8') as f:
    data = json.load(f)

count = 0
for k, v in data.items():
    if k == v:  # Untranslated
        trans = translate_text(k)
        if trans != k:
            data[k] = trans
            count += 1
        time.sleep(0.1)  # sleep to avoid rate limit

with open(file_path, 'w', encoding='utf-8') as f:
    json.dump(data, f, ensure_ascii=False, indent=4)

print(f"Successfully translated {count} keys.")
