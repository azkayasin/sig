inp = raw_input()

a,b = inp.split('/')
a = a.split(',')
b = b.split(',')

print "x1="+str(a[0])+"&y1="+str(a[1])+"&x2="+str(b[0])+"&y2="+str(b[1])