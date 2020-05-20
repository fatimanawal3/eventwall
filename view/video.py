import cv2
import numpy as np
import glob
 
img_array = []
for filename in glob.glob('/home/asus/Desktop/POKA/a/*.jpg'):
    img = cv2.imread(filename)
    height, width, layers = img.shape
    size = (width,height)
    img_array.append(img)

fourcc = cv2.VideoWriter_fourcc(*'X264')
out = cv2.VideoWriter('videos/video.mp4', fourcc, 3, size)

for i in range(len(img_array)):
    out.write(img_array[i])
    # print(img_array)
out.release()
