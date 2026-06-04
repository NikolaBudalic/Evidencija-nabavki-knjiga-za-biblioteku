import os, re, json
root=os.getcwd()
targets=['knjigaSnimi.php','knjigaSnimiSP.php','nabavkaSnimi.php','prijavaprovera.php']
files=[]
for dirpath,dirnames,filenames in os.walk(root):
    for fname in filenames:
        if fname.lower().endswith(('.php','.js','.css','.html')):
            files.append(os.path.relpath(os.path.join(dirpath,fname), root).replace('\\','/'))
files.sort()
patterns={
 'action': re.compile(r'action\s*=\s*["\']([^"\']+)["\']', re.I),
 'include': re.compile(r'\b(require_once|require|include)\s*(?:\(|)\s*["\']([^"\']+)["\']', re.I),
 'header': re.compile(r'header\s*\(\s*["\']Location:\s*([^"\']+)["\']', re.I),
 'href': re.compile(r'href\s*=\s*["\']([^"\']+)["\']', re.I),
 'jsredir': re.compile(r'\b(window\.location\.|location\.href|location\.replace|location\.assign|document\.location\b|location\s*=)\s*\(?\s*["\']?([^"\';)]+)', re.I),
 'ajax': re.compile(r'\b(fetch|axios|open)\s*\(\s*["\']([^"\']+)["\']', re.I),
}
result={}
for t in targets:
    result[t]={'references':[],'controllers_exist':False}
    result[t]['controllers_exist']=os.path.exists(os.path.join(root, 'controller', t))

for f in files:
    path=os.path.join(root,f)
    try:
        txt=open(path,'r',encoding='utf-8',errors='ignore').read()
    except Exception as e:
        continue
    for t in targets:
        # direct filename occurrences
        if t in txt:
            i=txt.find(t)
            snippet=txt[max(0,i-60):i+len(t)+60].replace('\n',' ')
            result[t]['references'].append({'file':f,'type':'contains','snippet':snippet})
        # patterns
        for ptype,pat in patterns.items():
            for m in pat.finditer(txt):
                val = m.group(1) if ptype!='include' else m.group(2)
                if val and (val.endswith(t) or val==t or val.endswith('/'+t) or os.path.basename(val)==t):
                    result[t]['references'].append({'file':f,'type':ptype,'match':val,'line_snippet':txt[max(0,m.start()-80):m.end()+80].replace('\n',' ')})
# dedupe references by file+type
for t in targets:
    uniq=[]; seen=set()
    for r in result[t]['references']:
        key=(r.get('file'), r.get('type'), r.get('match', r.get('snippet','')))
        if key not in seen:
            uniq.append(r); seen.add(key)
    result[t]['references']=uniq
# write result
open('root_handler_refs.json','w',encoding='utf-8').write(json.dumps(result, indent=2, ensure_ascii=False))
print('WROTE root_handler_refs.json')
