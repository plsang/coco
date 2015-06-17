# Written by Duy Le - ledduy@ieee.org
# Last update Jun 26, 2012
#!/bin/sh
# Force to use shell sh. Note that #$ is SGE command
#$ -S /bin/sh
# Force to limit hosts running jobs
#$ -q all.q@@bc5hosts
# Log starting time
date 
# for opencv shared lib

PATH=/net/per610a/export/das11f/plsang/usr/bin:/net/per900a/raid0/plsang/software/gcc-4.8.1/release/bin:/net/per900a/raid0/plsang/software/ffmpeg-2.0/release-shared/bin:/net/per900a/raid0/plsang/software/scala/bin:/net/per900a/raid0/plsang/usr.local/bin:/net/per900a/raid0/plsang/software/openmpi-1.6.5/release-shared/bin:$PATH

LD_LIBRARY_PATH=/net/per610a/export/das11f/plsang/usr/lib:/net/per900a/raid0/plsang/usr.local/lib64:/net/per900a/raid0/plsang/software/ffmpeg-2.0/release-shared/lib:/net/per900a/raid0/plsang/software/gcc-4.8.1/release/lib:/net/per900a/raid0/plsang/software/opencv-2.4.6.1/release/lib:/net/per900a/raid0/plsang/usr.local/lib:/net/per900a/raid0/plsang/software/openmpi-1.6.5/release-shared/lib:/usr/local/lib:/net/per900a/raid0/plsang/software/openssl-1.0.1g:$LD_LIBRARY_PATH

export PATH
export LD_LIBRARY_PATH

export PYTHONPATH=/net/per610a/export/das11f/plsang/usr/lib/python2.7/site-packages:$PYTHONPATH

# Log info of the job to output file  *** CHANGED ***
echo [$HOSTNAME] [$JOB_ID] [fast_rcnn_sge.py --cpu --set $1 --s $2 --e $3]
# change to the code dir  --> NEW!!! *** CHANGED ***

python /net/per610a/export/das11f/plsang/coco2014/fast-rcnn/fastrcnn_detect_object.py --cpu --set $1 --s $2 --e $3

# Log ending time
date

