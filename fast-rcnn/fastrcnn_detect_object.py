#!/usr/bin/env python

# --------------- ORIGINAL VERION BY ---------------------
# Fast R-CNN
# Copyright (c) 2015 Microsoft
# Licensed under The MIT License [see LICENSE for details]
# Written by Ross Girshick
# --------------------------------------------------------
#

"""
Demo script showing detections in sample images.

See README.md for installation instructions before running.
"""
import matplotlib
matplotlib.use('Agg')

import _init_paths
from fast_rcnn.config import cfg
from fast_rcnn.test import im_detect
from utils.cython_nms import nms
from utils.timer import Timer

import matplotlib.pyplot as plt
import numpy as np
import scipy.io as sio
import caffe, os, sys, cv2
import argparse

import glob
import pickle

CLASSES = ('__background__',
           'aeroplane', 'bicycle', 'bird', 'boat',
           'bottle', 'bus', 'car', 'cat', 'chair',
           'cow', 'diningtable', 'dog', 'horse',
           'motorbike', 'person', 'pottedplant',
           'sheep', 'sofa', 'train', 'tvmonitor')

NETS = {'vgg16': ('VGG16',
                  'vgg16_fast_rcnn_iter_40000.caffemodel'),
        'vgg_cnn_m_1024': ('VGG_CNN_M_1024',
                           'vgg_cnn_m_1024_fast_rcnn_iter_40000.caffemodel'),
        'caffenet': ('CaffeNet',
                     'caffenet_fast_rcnn_iter_40000.caffemodel')}

def detect(net, image_set, image_name, output_file):
    """Detect object classes in an image using pre-computed object proposals."""
    # Load pre-computed Selected Search object proposals
    #box_file = os.path.join(coco_root, 'boxes', image_set, image_name + '.mat')
    box_file = os.path.join(coco_root, 'boxes_full', image_set, image_name + '.mat')
    
    if not os.path.exists(box_file):
        print 'File does not exist', box_file
        return
        
    obj_proposals = sio.loadmat(box_file)['boxes']

    # Load the demo image
    im_file = os.path.join(coco_root, 'images', image_set, image_name + '.jpg')
    im = cv2.imread(im_file)

    # Detect all object classes and regress object bounds
    timer = Timer()
    timer.tic()
    scores, boxes = im_detect(net, im, obj_proposals)
    timer.toc()
    print ('Detection took {:.3f}s for '
           '{:d} object proposals').format(timer.total_time, boxes.shape[0])
	
    np.savez(output_file, scores=scores, boxes=boxes)
        
    #with open(output_file, 'w') as f:
    #    pickle.dump([scores, boxes], f)
	
def parse_args():
    """Parse input arguments."""
    parser = argparse.ArgumentParser(description='Train a Fast R-CNN network')
    parser.add_argument('--gpu', dest='gpu_id', help='GPU device id to use [0]',
                        default=0, type=int)
    parser.add_argument('--cpu', dest='cpu_mode',
                        help='Use CPU mode (overrides --gpu)',
                        action='store_true')
    parser.add_argument('--net', dest='demo_net', help='Network to use [vgg16]',
                        choices=NETS.keys(), default='vgg16')

    parser.add_argument('--set', dest='image_set', help='Image set')
    
    parser.add_argument('--s', dest='start_img', help='start image')
    
    parser.add_argument('--e', dest='end_img', help='end image')
						
    args = parser.parse_args()
    
    return args

if __name__ == '__main__':
    args = parse_args()

    prototxt = os.path.join(cfg.ROOT_DIR, 'models', NETS[args.demo_net][0],
                            'test.prototxt')
    caffemodel = os.path.join(cfg.ROOT_DIR, 'data', 'fast_rcnn_models',
                              NETS[args.demo_net][1])

    if not os.path.isfile(caffemodel):
        raise IOError(('{:s} not found.\nDid you run ./data/script/'
                       'fetch_fast_rcnn_models.sh?').format(caffemodel))

    if args.cpu_mode:
        caffe.set_mode_cpu()
    else:
        caffe.set_mode_gpu()
        caffe.set_device(args.gpu_id)
    net = caffe.Net(prototxt, caffemodel, caffe.TEST)
	
    print '\n\nLoaded network {:s}'.format(caffemodel)
    
    coco_root = '/net/per610a/export/das11f/plsang/coco2014'
	
    #load image list
    img_dir = os.path.join(coco_root, 'images', args.image_set)
    imglist = glob.glob(img_dir + '/*.jpg')
    
    start_img = int(args.start_img)
    end_img = int(args.end_img)
    
    for ii in range(start_img, end_img):
        img_name = os.path.splitext(os.path.basename(imglist[ii]))[0]
        #output_file = os.path.join(coco_root, 'fast_rcnn_boxes', args.image_set, img_name + '.npz')
        output_file = os.path.join(coco_root, 'fast_rcnn_boxes_full', args.image_set, img_name + '.npz')
        if os.path.exists(output_file):
            continue
        output_dir = os.path.dirname(output_file)
        if not os.path.exists(output_dir):
            os.makedirs(output_dir)
            
        print '----', ii, 'Detecting objects in image: ' + img_name
        detect(net, args.image_set, img_name, output_file)
	
