#!/usr/bin/env python

# Draw detected objects

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

                     
def vis_detections(im, class_name, dets, ax, thresh=0.5):
    """Draw detected bounding boxes."""
    inds = np.where(dets[:, -1] >= thresh)[0]
    if len(inds) == 0:
        return

    im = im[:, :, (2, 1, 0)]
    #fig, ax = plt.subplots(figsize=(12, 12))
    ax.imshow(im, aspect='equal')
    for i in inds:
        bbox = dets[i, :4]
        score = dets[i, -1]

        ax.add_patch(
            plt.Rectangle((bbox[0], bbox[1]),
                          bbox[2] - bbox[0],
                          bbox[3] - bbox[1], fill=False,
                          edgecolor='red', linewidth=3.5)
            )
        ax.text(bbox[0], bbox[1] - 2,
                '{:s} {:.3f}'.format(class_name, score),
                bbox=dict(facecolor='blue', alpha=0.5),
                fontsize=14, color='white')

    ax.set_title(('{} detections with '
                  'p({} | box) >= {:.1f}').format(class_name, class_name,
                                                  thresh),
                  fontsize=14)
    plt.axis('off')
    plt.tight_layout()
    plt.draw()

    
def draw (net, image_set, image_name, output_file, ssmode):
    """Detect object classes in an image using pre-computed object proposals."""
    # Load pre-computed Selected Search object proposals
    if ssmode == 'full':
        box_file = os.path.join(coco_root, 'fast_rcnn_boxes_full', image_set, image_name + '.npz')
    else:
        box_file = os.path.join(coco_root, 'fast_rcnn_boxes', image_set, image_name + '.npz')
        
    if not os.path.exists(box_file):
        print 'File does not exist', box_file
        return
	
    arr = np.load(box_file)
    scores = arr['scores']
    boxes = arr['boxes']
        
	# Visualize detections for each class
    fig = plt.figure( figsize=(12, 12), dpi=80)
    ax = fig.add_subplot(111)
    
    im_file = os.path.join(coco_root, 'images', image_set, image_name + '.jpg')
    im = cv2.imread(im_file)
    
    CONF_THRESH = 0.1
    NMS_THRESH = 0.1
    for cls in CLASSES:
        if cls == '__background__':
            continue
        try: 	
            cls_ind = CLASSES.index(cls)
            cls_boxes = boxes[:, 4*cls_ind:4*(cls_ind + 1)]
            cls_scores = scores[:, cls_ind]
            dets = np.hstack((cls_boxes,
                          cls_scores[:, np.newaxis])).astype(np.float32)
            keep = nms(dets, NMS_THRESH)
            dets = dets[keep, :]
            print 'All {} detections with p({} | box) >= {:.1f}'.format(cls, cls,
                                                                    CONF_THRESH)
            vis_detections(im, cls, dets, ax, thresh=CONF_THRESH)
        except:
            pass
			
    plt.savefig(output_file)
    
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
    
    parser.add_argument('--img', dest='image_name', help='Image name')
    
    parser.add_argument('--ssmode', dest='ssmode', help='Selective Search mode', default='fast')
    
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
	
    if args.image_name:
        if args.ssmode == 'full':
            output_file = os.path.join(coco_root, 'fast_rcnn_draw_full', args.image_set, args.image_name + '.jpg')
        else:
            output_file = os.path.join(coco_root, 'fast_rcnn_draw', args.image_set, args.image_name + '.jpg')
            
        if os.path.exists(output_file):
            exit()
            
        output_dir = os.path.dirname(output_file)
        if not os.path.exists(output_dir):
            os.makedirs(output_dir)
            
        print '----Detecting objects in image: ' + args.image_name
        draw(net, args.image_set, args.image_name, output_file, args.ssmode)
        
    else:
        #load image list
        img_dir = os.path.join(coco_root, 'images', args.image_set)
        imglist = glob.glob(img_dir + '/*.jpg')
        
        
        start_img = int(args.start_img)
        end_img = int(args.end_img)
        
        for ii in range(start_img, end_img):
            img_name = os.path.splitext(os.path.basename(imglist[ii]))[0]
            if args.ssmode == 'full':
                output_file = os.path.join(coco_root, 'fast_rcnn_draw_full', args.image_set, img_name + '.jpg')
            else:
                output_file = os.path.join(coco_root, 'fast_rcnn_draw', args.image_set, img_name + '.jpg')
                
            if os.path.exists(output_file):
                continue
            output_dir = os.path.dirname(output_file)
            if not os.path.exists(output_dir):
                os.makedirs(output_dir)
                
            print '----', ii, 'Detecting objects in image: ' + img_name
            draw(net, args.image_set, img_name, output_file, args.ssmode)
	
