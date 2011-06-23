#!/bin/bash
rm -rf debian/usr/share/wt/*
cp -r ../../* debian/usr/share/wt
rm -rf debian/usr/share/wt/nbproject
rm -rf debian/usr/share/wt/pkg
dpkg-deb --build debian
mv debian.deb wt-$1-all.deb
rm -rf debian/usr/share/wt/*
